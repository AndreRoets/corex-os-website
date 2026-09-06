<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessage;
use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact', ['page' => Page::where('key', 'contact')->first()]);
    }

    /**
     * The recipient and whether mail is even configured never reach the
     * client either way — the visitor always sees the same generic success,
     * so nothing here leaks how the site is set up.
     */
    public function store(ContactRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('company');

        $recipient = SiteSetting::current()->contact_recipient_email
            ?: config('mail.from.address');

        if ($recipient) {
            try {
                Mail::to($recipient)->send(new ContactMessage($data));
            } catch (Throwable $e) {
                Log::error('Contact form message could not be emailed', [
                    'error' => $e->getMessage(),
                    'email' => $data['email'],
                ]);
            }
        } else {
            Log::warning('Contact form submitted with no recipient configured', ['email' => $data['email']]);
        }

        return back()->with('contact_success', "Thanks {$data['name']} — your message is in. We'll be in touch soon.");
    }
}
