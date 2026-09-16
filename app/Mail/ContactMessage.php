<?php

namespace App\Mail;

use App\Models\ContactRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactRequest $enquiry) {}

    public function envelope(): Envelope
    {
        $topic = $this->enquiry->topicLabel();

        return new Envelope(
            subject: 'Website enquiry'.($topic ? " ({$topic})" : '')." — {$this->enquiry->name}",
            // Replying to the notification replies to the person who wrote.
            replyTo: [new Address($this->enquiry->email, $this->enquiry->name)],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.contact-message',
            with: [
                'enquiry' => $this->enquiry,
                'channel' => $this->enquiry->channel(),
                'adminUrl' => route('admin.enquiries.show', $this->enquiry),
            ],
        );
    }
}
