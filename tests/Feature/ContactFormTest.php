<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_contact_page_renders(): void
    {
        $this->get(route('contact'))->assertOk();
    }

    public function test_a_message_is_emailed_to_the_configured_recipient(): void
    {
        Mail::fake();
        SiteSetting::current()->update(['contact_recipient_email' => 'sales@example.com']);

        $this->post(route('contact.store'), [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '082 000 0000',
            'message' => 'How does this work?',
            'company' => '',
        ])->assertRedirect()->assertSessionHas('contact_success');

        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) {
            return $mail->hasTo('sales@example.com')
                && $mail->hasReplyTo('jane@example.com')
                && $mail->contact['message'] === 'How does this work?';
        });
    }

    /**
     * No recipient configured must never surface as an error to the visitor
     * — see App\Http\Controllers\ContactController::store(). The generic
     * success message is shown either way, so nothing about the site's
     * configuration ever leaks to the client.
     */
    public function test_a_missing_recipient_still_shows_the_generic_success(): void
    {
        Mail::fake();
        config(['mail.from.address' => null]);
        SiteSetting::current()->update(['contact_recipient_email' => null]);

        $this->post(route('contact.store'), [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'message' => 'How does this work?',
            'company' => '',
        ])->assertRedirect()->assertSessionHas('contact_success');

        Mail::assertNothingSent();
    }

    public function test_a_filled_honeypot_is_rejected_silently(): void
    {
        Mail::fake();

        $this->post(route('contact.store'), [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'message' => 'Buy my product',
            'company' => 'Definitely a bot',
        ])->assertSessionHasErrors('company');

        Mail::assertNothingSent();
    }

    public function test_the_route_is_rate_limited(): void
    {
        // The array cache store used in tests persists across test methods
        // within the process, so a flush guarantees this test starts with a
        // clean throttle count rather than inheriting one from a test above.
        Cache::flush();
        Mail::fake();

        for ($i = 0; $i < 6; $i++) {
            $this->post(route('contact.store'), [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'message' => 'Message number '.$i,
                'company' => '',
            ]);
        }

        $this->post(route('contact.store'), [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'message' => 'One too many',
            'company' => '',
        ])->assertStatus(429);
    }
}
