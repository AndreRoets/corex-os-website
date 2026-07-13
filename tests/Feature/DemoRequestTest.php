<?php

namespace Tests\Feature;

use App\Mail\DemoRequested;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DemoRequestTest extends TestCase
{
    public function test_a_demo_request_is_emailed_to_the_demo_inbox(): void
    {
        Mail::fake();

        $this->post(route('demo.store'), [
            'name' => 'M. Naidoo',
            'agency' => 'Ridge Realty',
            'email' => 'm.naidoo@example.com',
            'phone' => '082 000 0000',
            'agents' => '12',
            'message' => 'Keen to see the deal pipeline.',
            'consent' => '1',
        ])->assertRedirect(route('home').'#demo');

        Mail::assertSent(DemoRequested::class, function (DemoRequested $mail) {
            return $mail->hasTo(config('mail.demo.address'))
                && $mail->hasReplyTo('m.naidoo@example.com')
                && $mail->demo['agency'] === 'Ridge Realty';
        });
    }

    public function test_a_honeypot_submission_is_rejected_and_sends_nothing(): void
    {
        Mail::fake();

        $this->post(route('demo.store'), [
            'name' => 'Bot',
            'agency' => 'Bot Co',
            'email' => 'bot@example.com',
            'consent' => '1',
            'website' => 'http://spam.example',
        ])->assertSessionHasErrors('website');

        Mail::assertNothingSent();
    }
}
