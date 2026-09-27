<?php

namespace App\Mail;

use App\Models\ContactRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * The demo login, sent to the person who asked for it.
 *
 * The page reveals the same credentials on the spot; this is the copy they
 * still have tomorrow, when they are standing somewhere with the app open and
 * the browser tab long closed. Replies come to the sales inbox, because
 * someone who answers this email is a lead.
 */
class MobileDemoDetails extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactRequest $enquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your CoreX OS mobile app demo login',
            replyTo: [new Address(config('mail.demo.address'), config('mail.demo.name'))],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.mobile-demo-details',
            with: [
                'name' => $this->enquiry->name,
                'demoEmail' => config('corex.mobile_app.demo_email'),
                'demoPassword' => config('corex.mobile_app.demo_password'),
                'androidUrl' => config('corex.mobile_app.android_url'),
                'iosUrl' => config('corex.mobile_app.ios_url'),
                'contactEmail' => config('corex.contact_email'),
            ],
        );
    }
}
