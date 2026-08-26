<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CoreX\CoreXUnavailable;
use App\Services\CoreX\WebinarClient;
use App\Support\Sast;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Who registered, and the two downloads that are the point of the screen.
 *
 * This list is the only record these people exist in — webinar registrants are
 * deliberately not added to CoreX's CRM. So it is personal data with no backup
 * copy and no second home: it lives behind the admin login, it is never on a
 * public route, never in a URL, never in client-side JavaScript, and it is not
 * written to this website's database or its logs on the way past.
 */
class RegistrationController extends Controller
{
    /** CoreX's own page size. Matching it keeps our paging honest. */
    private const PER_PAGE = 100;

    public function __construct(private readonly WebinarClient $corex) {}

    public function index(Request $request, string $slug): View|RedirectResponse
    {
        $page = max(1, (int) $request->integer('page', 1));
        $search = trim((string) $request->query('q', ''));

        try {
            $result = $this->corex->registrations($slug, $page, self::PER_PAGE);
        } catch (CoreXUnavailable $e) {
            return redirect()
                ->route('admin.webinars.index')
                ->with('admin_error', $e->isAuthFailure
                    ? 'CoreX rejected our credentials — check COREX_WEBINAR_ADMIN_TOKEN on the server.'
                    : 'CoreX could not be reached just now. Please try again in a moment.');
        }

        if ($result->notFound()) {
            return redirect()
                ->route('admin.webinars.index')
                ->with('admin_error', 'That webinar could not be found, or the registrant endpoints are not live yet.');
        }

        $registrations = (array) $result->get('registrations');

        return view('admin.registrations.index', [
            'slug' => $slug,
            'webinar' => (array) $result->get('webinar'),
            'registrations' => $this->sortNewestFirst($this->filter($registrations, $search)),
            'meta' => (array) $result->get('meta'),
            'search' => $search,
            'totalOnPage' => count($registrations),
        ]);
    }

    /**
     * Stream a CSV straight through to the browser.
     *
     * The bytes are not parsed, re-encoded or reordered. The Zoom file in
     * particular is column-for-column what Zoom's bulk-registrant importer
     * expects, and CoreX is the one place that definition should live —
     * reshaping it here would create a second copy of it that nobody would
     * think to update when Zoom changes the template.
     *
     * It is streamed rather than buffered so a large webinar does not have to
     * fit in this process's memory before anyone can download it.
     */
    public function download(string $slug, string $format): StreamedResponse|RedirectResponse
    {
        if (! in_array($format, ['zoom', 'full'], true)) {
            abort(404);
        }

        try {
            ['stream' => $stream, 'headers' => $headers] = $this->corex->registrationsCsv($slug, $format);
        } catch (CoreXUnavailable $e) {
            return back()->with('admin_error', $e->isAuthFailure
                ? 'CoreX rejected our credentials — check COREX_WEBINAR_ADMIN_TOKEN on the server.'
                : 'The download could not be started. Please try again in a moment.');
        }

        // Who exported the registrant list, and when. Not what was in it — the
        // point of logging a PII export is accountability, not a second copy of
        // the data sitting in a log file.
        Log::info('Registrant CSV exported', [
            'slug' => $slug,
            'format' => $format,
            'user_id' => auth()->id(),
        ]);

        return response()->stream(function () use ($stream) {
            while (! $stream->eof()) {
                echo $stream->read(8192);

                // Push each chunk out as it arrives rather than letting PHP
                // accumulate the whole file behind the buffer — otherwise
                // "streaming" would only be true of the CoreX half of the trip.
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            }

            $stream->close();
        }, 200, $headers);
    }

    /**
     * Search across the things someone actually remembers about a registrant.
     *
     * Applied to the current page only, and the screen says so — filtering
     * here rather than at CoreX would otherwise imply it had searched all of
     * them, which for a webinar with several hundred sign-ups it has not.
     *
     * @param  array<int, array<string, mixed>>  $registrations
     * @return array<int, array<string, mixed>>
     */
    private function filter(array $registrations, string $search): array
    {
        if ($search === '') {
            return $registrations;
        }

        $needle = Str::lower($search);

        return array_values(array_filter($registrations, function (array $row) use ($needle) {
            $haystack = Str::lower(implode(' ', [
                $row['first_name'] ?? '',
                $row['last_name'] ?? '',
                $row['email'] ?? '',
                $row['company_name'] ?? '',
            ]));

            return str_contains($haystack, $needle);
        }));
    }

    /**
     * Newest first — the useful default, because the reason to open this screen
     * is almost always "who came in since I last looked".
     *
     * @param  array<int, array<string, mixed>>  $registrations
     * @return array<int, array<string, mixed>>
     */
    private function sortNewestFirst(array $registrations): array
    {
        // Compared as instants, not as strings: two rows written either side of
        // an offset change would sort by their text and land in the wrong order.
        usort($registrations, function (array $a, array $b) {
            return (Sast::parse($b['registered_at'] ?? null)?->getTimestamp() ?? 0)
                <=> (Sast::parse($a['registered_at'] ?? null)?->getTimestamp() ?? 0);
        });

        return $registrations;
    }
}
