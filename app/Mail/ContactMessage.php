<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, string|null>  $contact  Validated contact-form fields.
     */
    public function __construct(public array $contact) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Website enquiry — {$this->contact['name']}",
            replyTo: [new Address($this->contact['email'], $this->contact['name'])],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.contact-message',
            with: ['contact' => $this->contact],
        );
    }
}
