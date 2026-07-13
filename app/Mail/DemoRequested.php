<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DemoRequested extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, string|null>  $demo  Validated demo-request fields.
     */
    public function __construct(public array $demo)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Demo request — {$this->demo['agency']}",
            // Replying to the notification replies to the person who asked.
            replyTo: [new Address($this->demo['email'], $this->demo['name'])],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.demo-requested',
            with: ['demo' => $this->demo],
        );
    }
}
