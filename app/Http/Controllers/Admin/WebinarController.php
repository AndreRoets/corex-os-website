<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CoreX\CoreXResult;
use App\Services\CoreX\CoreXUnavailable;
use App\Services\CoreX\WebinarClient;
use App\Support\Sast;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Create, edit and archive webinars — all of it living in CoreX.
 *
 * Every screen here is rendered from a call made on this request. Nothing is
 * cached and nothing is written to our database, because CoreX derives each
 * registrant's demo-access deadline from the webinar record. A copy of that
 * record here that drifted by a day would hand people an access deadline that
 * differs from the one actually enforced, and nothing would report the
 * discrepancy — they would simply be locked out early, or keep access they
 * should have lost.
 */
class WebinarController extends Controller
{
    /**
     * The fields the create/edit form owns. Kept in one list because both the
     * form and the save need to agree on it exactly.
     */
    private const FORM_FIELDS = [
        'title',
        'slug',
        'description',
        'starts_at',
        'duration_minutes',
        // join_url is deliberately absent. It is set on the registrants screen,
        // where pasting it also emails it to everyone already signed up. Leaving
        // it here would let this form overwrite a link that had already gone out.
        'access_ends_days_after',
        'reminder_hours_before',
    ];

    public function __construct(private readonly WebinarClient $corex) {}

    public function index(Request $request): View
    {
        $includeArchived = $request->boolean('archived');

        try {
            $result = $this->corex->webinars($includeArchived);
        } catch (CoreXUnavailable $e) {
            return view('admin.webinars.index', [
                'webinars' => [],
                'includeArchived' => $includeArchived,
                'problem' => $this->problem($e),
            ]);
        }

        return view('admin.webinars.index', [
            'webinars' => $this->rows($result),
            'includeArchived' => $includeArchived,
            'problem' => $result->notFound() ? $this->notLiveYet() : null,
        ]);
    }

    public function create(): View
    {
        return view('admin.webinars.form', [
            'webinar' => null,
            'slug' => null,
            'registrationCount' => 0,
            'unknownFields' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse|View
    {
        try {
            $result = $this->corex->createWebinar($this->payload($request));
        } catch (CoreXUnavailable $e) {
            return back()->withInput()->with('admin_error', $this->problem($e));
        }

        if ($result->invalid()) {
            return back()->withInput()->withErrors($result->errors());
        }

        if (! $result->ok()) {
            return back()->withInput()->with('admin_error', $result->message() ?? 'The webinar could not be created.');
        }

        return redirect()
            ->route('admin.webinars.index')
            ->with('admin_status', 'Webinar created. The registration link is in the list below.');
    }

    public function edit(string $slug): View|RedirectResponse
    {
        try {
            $result = $this->corex->adminWebinar($slug);
            $listRow = $this->findRow($slug);
        } catch (CoreXUnavailable $e) {
            return redirect()->route('admin.webinars.index')->with('admin_error', $this->problem($e));
        }

        if ($result->notFound()) {
            return redirect()->route('admin.webinars.index')->with('admin_error', 'That webinar no longer exists.');
        }

        $webinar = array_merge($listRow, (array) $result->get('webinar'));

        return view('admin.webinars.form', [
            'webinar' => $webinar,
            'slug' => $slug,
            'registrationCount' => (int) ($listRow['registration_count'] ?? 0),
            // Anything CoreX did not send back cannot be shown in the form, and
            // therefore must not be saved from it. See payload().
            'unknownFields' => array_values(array_filter(
                ['access_ends_days_after', 'reminder_hours_before'],
                fn (string $field) => ! array_key_exists($field, $webinar),
            )),
        ]);
    }

    public function update(Request $request, string $slug): RedirectResponse
    {
        try {
            $result = $this->corex->updateWebinar($slug, $this->payload($request));
        } catch (CoreXUnavailable $e) {
            return back()->withInput()->with('admin_error', $this->problem($e));
        }

        if ($result->invalid()) {
            return back()->withInput()->withErrors($result->errors());
        }

        if (! $result->ok()) {
            return back()->withInput()->with('admin_error', $result->message() ?? 'The changes could not be saved.');
        }

        // The slug may have changed under us — follow CoreX's copy, not ours.
        $newSlug = (string) (data_get($result->json, 'webinar.slug') ?: $slug);

        return redirect()
            ->route('admin.webinars.index')
            ->with('admin_status', 'Changes saved.'.($newSlug !== $slug ? ' The registration link has changed — hand out the new one.' : ''));
    }

    /**
     * Archive. There is no delete: the registration link stops working
     * immediately, and everyone already registered keeps the demo access they
     * were promised.
     */
    public function destroy(string $slug): RedirectResponse
    {
        try {
            $result = $this->corex->archiveWebinar($slug);
        } catch (CoreXUnavailable $e) {
            return back()->with('admin_error', $this->problem($e));
        }

        if (! $result->ok() && ! $result->notFound()) {
            return back()->with('admin_error', $result->message() ?? 'The webinar could not be archived.');
        }

        return redirect()
            ->route('admin.webinars.index')
            ->with('admin_status', 'Webinar archived. Nobody new can sign up; everyone already registered keeps their demo access.');
    }

    /**
     * Turn the form into the JSON CoreX expects.
     */
    private function payload(Request $request): array
    {
        $input = $request->only(self::FORM_FIELDS);

        // A datetime-local input has no offset at all. Sending it raw would let
        // CoreX guess, and sending it as UTC would move every webinar two
        // hours. It is South African time and it goes out saying so.
        $input['starts_at'] = Sast::fromInput($input['starts_at'] ?? null);

        foreach (['duration_minutes', 'access_ends_days_after', 'reminder_hours_before'] as $number) {
            $input[$number] = is_numeric($input[$number] ?? null) ? (int) $input[$number] : null;
        }

        // Fields CoreX never sent us cannot be edited, so they are left out of
        // the save entirely rather than written back as blank. Omitting a key
        // leaves it untouched; sending it empty would quietly delete a joining
        // link that nobody meant to touch.
        foreach ((array) $request->input('_unknown', []) as $unknown) {
            if (in_array($unknown, self::FORM_FIELDS, true)) {
                unset($input[$unknown]);
            }
        }

        // Drop nulls only. An empty string is a real instruction — "clear the
        // description" — whereas null here means "the form had nothing to say",
        // and CoreX should apply its own default or its own required rule.
        return array_filter($input, fn ($value) => $value !== null);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function rows(CoreXResult $result): array
    {
        $webinars = $result->get('webinars');

        return is_array($webinars) ? array_values($webinars) : [];
    }

    /**
     * The list row for one webinar — it carries the registration count and the
     * archived flag, which the single-webinar read does not.
     *
     * @return array<string, mixed>
     */
    private function findRow(string $slug): array
    {
        $rows = $this->rows($this->corex->webinars(includeArchived: true));

        foreach ($rows as $row) {
            if (($row['slug'] ?? null) === $slug) {
                return $row;
            }
        }

        return [];
    }

    private function problem(CoreXUnavailable $e): string
    {
        return $e->isAuthFailure
            ? 'CoreX rejected our credentials. The admin token is wrong, revoked, or missing — check COREX_WEBINAR_ADMIN_TOKEN on the server.'
            : 'CoreX could not be reached just now. Nothing has been changed. Please try again in a moment.';
    }

    private function notLiveYet(): string
    {
        return 'CoreX does not yet answer on the webinar admin endpoints. This console is built and waiting — it will fill in as soon as the CoreX team switches them on.';
    }
}
