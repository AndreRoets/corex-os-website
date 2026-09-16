<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use App\Models\ContactRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Jane Smith',
            'agency' => 'Ridge Realty',
            'email' => 'jane@example.com',
            'phone' => '082 000 0000',
            'topic' => 'sales',
            'message' => 'How does this work?',
            'consent' => '1',
        ], $overrides);
    }

    public function test_the_contact_page_renders_with_the_form(): void
    {
        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('Send message')
            ->assertSee('Sales &amp; pricing', false);
    }

    public function test_the_contact_page_is_linked_from_the_site_navigation(): void
    {
        $this->get('/')->assertSee(route('contact'));
    }

    public function test_a_message_is_saved_and_emailed_to_the_demo_inbox(): void
    {
        Mail::fake();
        config(['mail.demo.address' => 'sales@example.com']);

        $this->post(route('contact.store'), $this->validPayload())
            ->assertRedirect(route('contact'))
            ->assertSessionHas('contact_success');

        $this->assertDatabaseHas('contact_requests', [
            'name' => 'Jane Smith',
            'agency' => 'Ridge Realty',
            'email' => 'jane@example.com',
            'topic' => 'sales',
            'message' => 'How does this work?',
        ]);

        $enquiry = ContactRequest::sole();
        $this->assertNotNull($enquiry->emailed_at);

        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) use ($enquiry) {
            return $mail->hasTo('sales@example.com')
                && $mail->hasReplyTo('jane@example.com')
                && $mail->enquiry->is($enquiry)
                && str_contains($mail->envelope()->subject, 'Sales & pricing');
        });
    }

    public function test_the_notification_email_renders(): void
    {
        $enquiry = ContactRequest::create($this->validPayload() + ['utm_source' => 'google', 'utm_medium' => 'cpc']);

        $html = (new ContactMessage($enquiry))->render();

        $this->assertStringContainsString('How does this work?', $html);
        $this->assertStringContainsString('Paid search', $html);
        $this->assertStringContainsString(route('admin.enquiries.show', $enquiry), $html);
    }

    /**
     * The whole point of keeping the row: the source that brought the person
     * to the site is captured on their first page view and lands on the
     * enquiry they send several pages later.
     */
    public function test_the_session_attribution_is_stored_on_the_enquiry(): void
    {
        Mail::fake();

        $this->get('/pricing?utm_source=google&utm_medium=cpc&utm_campaign=spring&gclid=abc123', [
            'referer' => 'https://www.google.com/',
        ])->assertOk();

        // A later page view does not overwrite the first touch.
        $this->get('/', ['referer' => config('app.url').'/pricing'])->assertOk();

        $this->post(route('contact.store'), $this->validPayload(), ['referer' => config('app.url').'/contact']);

        $enquiry = ContactRequest::sole();

        $this->assertSame('google', $enquiry->utm_source);
        $this->assertSame('cpc', $enquiry->utm_medium);
        $this->assertSame('spring', $enquiry->utm_campaign);
        $this->assertSame('abc123', $enquiry->gclid);
        $this->assertSame('https://www.google.com/', $enquiry->referrer);
        $this->assertStringContainsString('/pricing?', $enquiry->landing_page);
        $this->assertStringContainsString('utm_source=google', $enquiry->landing_page);
        $this->assertStringEndsWith('/contact', $enquiry->page_url);
        $this->assertNotNull($enquiry->first_seen_at);
        $this->assertSame('Paid search', $enquiry->channel());
    }

    public function test_a_later_campaign_click_replaces_a_plain_first_touch(): void
    {
        Mail::fake();

        $this->get('/')->assertOk();
        $this->get('/pricing?utm_source=newsletter&utm_medium=email')->assertOk();

        $this->post(route('contact.store'), $this->validPayload());

        $enquiry = ContactRequest::sole();

        $this->assertSame('newsletter', $enquiry->utm_source);
        $this->assertSame('Email', $enquiry->channel());
    }

    public function test_a_visit_with_no_source_is_direct(): void
    {
        Mail::fake();

        $this->get('/')->assertOk();
        $this->post(route('contact.store'), $this->validPayload());

        $enquiry = ContactRequest::sole();

        $this->assertNull($enquiry->referrer);
        $this->assertNull($enquiry->utm_source);
        $this->assertSame('Direct', $enquiry->channel());
    }

    /**
     * A dead SMTP host must never cost us the lead or show the visitor an
     * error — the row is already saved, and the console shows it unsent.
     */
    public function test_a_mail_failure_keeps_the_row_and_still_shows_success(): void
    {
        Mail::shouldReceive('to')->once()->andThrow(new \RuntimeException('SMTP down'));

        $this->post(route('contact.store'), $this->validPayload())
            ->assertRedirect(route('contact'))
            ->assertSessionHas('contact_success');

        $enquiry = ContactRequest::sole();
        $this->assertNull($enquiry->emailed_at);
    }

    public function test_consent_is_required(): void
    {
        Mail::fake();

        $this->post(route('contact.store'), $this->validPayload(['consent' => null]))
            ->assertSessionHasErrors('consent');

        $this->assertSame(0, ContactRequest::count());
        Mail::assertNothingSent();
    }

    public function test_an_unknown_topic_is_rejected(): void
    {
        Mail::fake();

        $this->post(route('contact.store'), $this->validPayload(['topic' => 'nonsense']))
            ->assertSessionHasErrors('topic');

        $this->assertSame(0, ContactRequest::count());
    }

    public function test_a_filled_honeypot_is_rejected_and_stores_nothing(): void
    {
        Mail::fake();

        $this->post(route('contact.store'), $this->validPayload(['website' => 'https://spam.example']))
            ->assertSessionHasErrors('website');

        $this->assertSame(0, ContactRequest::count());
        Mail::assertNothingSent();
    }

    public function test_the_success_view_fires_the_lead_conversion_when_analytics_is_configured(): void
    {
        Mail::fake();
        \App\Models\SiteSetting::current()->update([
            'ga4_measurement_id' => 'G-ABC123',
            'google_ads_conversion_id' => 'AW-999',
            'google_ads_conversion_label' => 'xyz',
        ]);

        $this->post(route('contact.store'), $this->validPayload());

        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('Message received')
            ->assertSee("gtag('event', \"generate_lead\"", false)
            ->assertSee('"AW-999/xyz"', false);
    }

    public function test_the_route_is_rate_limited(): void
    {
        // The array cache store used in tests persists across test methods
        // within the process, so a flush guarantees this test starts with a
        // clean throttle count rather than inheriting one from a test above.
        Cache::flush();
        Mail::fake();

        for ($i = 0; $i < 6; $i++) {
            $this->post(route('contact.store'), $this->validPayload(['message' => 'Message number '.$i]));
        }

        $this->post(route('contact.store'), $this->validPayload(['message' => 'One too many']))
            ->assertStatus(429);
    }
}
