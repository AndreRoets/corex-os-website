<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use App\Support\Attribution;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Messages sent through the contact page, and where each one came from.
 *
 * The email to the sales inbox is how the team hears about an enquiry; this
 * screen is how marketing sees the pattern — which channel, campaign or
 * landing page produced the people who actually wrote in.
 */
class EnquiryController extends Controller
{
    /** How far back the channel summary looks. */
    private const CHANNEL_WINDOW_DAYS = 90;

    public function index(Request $request): Response
    {
        $search = trim((string) $request->query('q', ''));

        $paginator = ContactRequest::query()
            ->search($search)
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        return Inertia::render('Admin/Enquiries/Index', [
            'enquiries' => collect($paginator->items())->map(fn (ContactRequest $e) => $this->row($e))->values(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'total' => $paginator->total(),
            ],
            'search' => $search,
            'channels' => $this->channels(),
            'channelWindowDays' => self::CHANNEL_WINDOW_DAYS,
        ]);
    }

    public function show(ContactRequest $enquiry): Response
    {
        return Inertia::render('Admin/Enquiries/Show', [
            'enquiry' => [
                ...$this->row($enquiry),
                'phone' => $enquiry->phone,
                'message' => $enquiry->message,
                'page_url' => $enquiry->page_url,
                'referrer' => $enquiry->referrer,
                'utm_term' => $enquiry->utm_term,
                'utm_content' => $enquiry->utm_content,
                'gclid' => $enquiry->gclid,
                'fbclid' => $enquiry->fbclid,
                'ip_address' => $enquiry->ip_address,
                'user_agent' => $enquiry->user_agent,
                'first_seen_at' => $this->sast($enquiry->first_seen_at),
                'emailed_at' => $this->sast($enquiry->emailed_at),
            ],
        ]);
    }

    /**
     * Everything, as a spreadsheet — the same search applied, so a filtered
     * list downloads as the filtered list.
     */
    public function download(Request $request): StreamedResponse
    {
        $search = trim((string) $request->query('q', ''));

        // Who exported it, not what was in it — see RegistrationController.
        Log::info('Contact enquiries CSV exported', [
            'search' => $search,
            'user_id' => auth()->id(),
        ]);

        $columns = [
            'id', 'received_at', 'name', 'email', 'phone', 'agency', 'topic', 'message', 'channel',
            'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'fbclid',
            'landing_page', 'referrer', 'page_url', 'first_seen_at', 'emailed_at',
        ];

        return response()->streamDownload(function () use ($search, $columns) {
            $out = fopen('php://output', 'w');

            fputcsv($out, $columns);

            ContactRequest::query()
                ->search($search)
                ->orderBy('id')
                ->chunk(200, function ($rows) use ($out) {
                    foreach ($rows as $e) {
                        fputcsv($out, [
                            $e->id,
                            $this->sast($e->created_at),
                            $e->name,
                            $e->email,
                            $e->phone,
                            $e->agency,
                            $e->topicLabel(),
                            $e->message,
                            $e->channel(),
                            $e->utm_source,
                            $e->utm_medium,
                            $e->utm_campaign,
                            $e->utm_term,
                            $e->utm_content,
                            $e->gclid,
                            $e->fbclid,
                            $e->landing_page,
                            $e->referrer,
                            $e->page_url,
                            $this->sast($e->first_seen_at),
                            $this->sast($e->emailed_at),
                        ]);
                    }
                });

            fclose($out);
        }, 'contact-enquiries-'.now()->format('Y-m-d').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * How many enquiries each channel produced recently, biggest first.
     *
     * @return list<array{label: string, count: int}>
     */
    private function channels(): array
    {
        return ContactRequest::query()
            ->where('created_at', '>=', now()->subDays(self::CHANNEL_WINDOW_DAYS))
            ->get(['id', ...Attribution::COLUMNS])
            ->countBy(fn (ContactRequest $e) => $e->channel())
            ->sortDesc()
            ->map(fn (int $count, string $label) => ['label' => $label, 'count' => $count])
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function row(ContactRequest $e): array
    {
        return [
            'id' => $e->id,
            'name' => $e->name,
            'email' => $e->email,
            'agency' => $e->agency,
            'topic' => $e->topicLabel(),
            'excerpt' => \Illuminate\Support\Str::limit($e->message, 90),
            'channel' => $e->channel(),
            'utm_source' => $e->utm_source,
            'utm_medium' => $e->utm_medium,
            'utm_campaign' => $e->utm_campaign,
            'landing_page' => $e->landing_page,
            'emailed' => $e->emailed_at !== null,
            'received_at' => $this->sast($e->created_at),
        ];
    }

    /**
     * ISO string in South African time, for resources/js/sast.js, which
     * slices the string rather than converting it.
     */
    private function sast(?Carbon $at): ?string
    {
        return $at?->timezone(config('corex.timezone'))->toIso8601String();
    }
}
