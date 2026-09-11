<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PricingEnquiry extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, string|null>  $enquiry  Validated enquiry fields.
     */
    public function __construct(public array $enquiry)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Pricing enquiry (40+ agents) — {$this->enquiry['agency']}",
            // Replying to the notification replies to the person who asked.
            replyTo: [new Address($this->enquiry['email'], $this->enquiry['name'])],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.pricing-enquiry',
            with: ['enquiry' => $this->enquiry],
        );
    }
}
