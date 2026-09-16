<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Mail\ContactMessage;
use App\Models\ContactRequest;
use App\Models\Page;
use App\Support\Attribution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact', [
            'page' => Page::where('key', 'contact')->first(),
            'topics' => ContactRequest::TOPICS,
        ]);
    }

    /**
     * Handle a message from the contact page.
     *
     * Saved first — with the session's attribution, so the enquiry can be
     * credited to whatever brought the person here — then emailed to the same
     * inbox demo requests go to. A dead SMTP host costs us the notification,
     * never the lead: the row is already there and the admin console lists it.
     */
    public function store(StoreContactRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['website', 'consent']);

        $enquiry = ContactRequest::create([
            ...$data,
            ...Attribution::current($request),
            'page_url' => Str::limit((string) $request->headers->get('referer', ''), 2000, '') ?: null,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 500, '') ?: null,
        ]);

        Log::channel('stack')->info('CoreX OS contact request', [
            'id' => $enquiry->id,
            'email' => $enquiry->email,
            'channel' => $enquiry->channel(),
        ]);

        try {
            Mail::to(config('mail.demo.address'), config('mail.demo.name'))
                ->send(new ContactMessage($enquiry));

            $enquiry->forceFill(['emailed_at' => now()])->save();
        } catch (Throwable $e) {
            Log::error('CoreX OS contact request could not be emailed', [
                'id' => $enquiry->id,
                'error' => $e->getMessage(),
                'email' => $enquiry->email,
            ]);
        }

        return redirect()
            ->route('contact')
            ->with('contact_success', "Thanks {$enquiry->name} — your message is in. We'll be in touch within one business day.");
    }
}
