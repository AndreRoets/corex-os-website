<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use App\Mail\MobileDemoDetails;
use App\Models\ContactRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MobileDemoAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'corex.mobile_app.demo_email' => 'Demo@corexweb.co.za',
            'corex.mobile_app.demo_password' => 'Corex@mobiledemo',
        ]);
    }

    /**
     * @return array<string, string|null>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'agency' => 'Ridge Realty',
            'consent' => '1',
        ], $overrides);
    }

    public function test_the_page_leads_with_the_store_links_and_not_a_simulator(): void
    {
        $response = $this->get(route('mobile-app'))->assertOk();

        // Escaped, because the Play Store URL carries a query string and Blade
        // writes it into the href as &amp;.
        $response->assertSee(config('corex.mobile_app.android_url'));
        $response->assertSee(config('corex.mobile_app.ios_url'));
        $response->assertSee('Get the demo login');

        // The credentials are behind the form until someone asks for them.
        $response->assertDontSee('Corex@mobiledemo');
    }

    public function test_asking_for_the_login_reveals_it_and_emails_both_sides(): void
    {
        Mail::fake();
        config(['mail.demo.address' => 'sales@example.com', 'mail.demo.name' => 'CoreX OS']);

        $this->post(route('mobile-app.demo'), $this->validPayload())
            ->assertRedirect(route('mobile-app').'#demo-login')
            ->assertSessionHas('mobile_demo_name', 'Jane Smith')
            ->assertSessionHas('mobile_demo_emailed', true);

        $this->assertDatabaseHas('contact_requests', [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'agency' => 'Ridge Realty',
            'topic' => 'mobile-demo',
        ]);

        $enquiry = ContactRequest::sole();
        $this->assertNotNull($enquiry->emailed_at);
        $this->assertSame('Mobile app demo login', $enquiry->topicLabel());

        // Us: the same notification the contact page produces, same inbox.
        Mail::assertSent(ContactMessage::class, fn (ContactMessage $mail) => $mail->hasTo('sales@example.com')
            && $mail->hasReplyTo('jane@example.com')
            && $mail->enquiry->is($enquiry));

        // Them: the credentials.
        Mail::assertSent(MobileDemoDetails::class, fn (MobileDemoDetails $mail) => $mail->hasTo('jane@example.com')
            && $mail->hasReplyTo('sales@example.com'));
    }

    public function test_the_revealed_page_shows_the_credentials_from_config(): void
    {
        Mail::fake();

        $this->post(route('mobile-app.demo'), $this->validPayload());

        $this->get(route('mobile-app'))
            ->assertOk()
            ->assertSee('Here you go, Jane Smith')
            ->assertSee('Demo@corexweb.co.za')
            ->assertSee('Corex@mobiledemo')
            ->assertSee('We&rsquo;ve emailed these to you as well', false);
    }

    public function test_the_credentials_are_gone_again_on_the_next_visit(): void
    {
        Mail::fake();

        $this->post(route('mobile-app.demo'), $this->validPayload());
        $this->get(route('mobile-app'))->assertSee('Corex@mobiledemo');

        $this->get(route('mobile-app'))
            ->assertOk()
            ->assertDontSee('Corex@mobiledemo')
            ->assertSee('Get the demo login');
    }

    public function test_the_credentials_email_renders(): void
    {
        $enquiry = ContactRequest::create($this->validPayload(['consent' => null]) + ['message' => 'x']);

        $html = (new MobileDemoDetails($enquiry))->render();

        $this->assertStringContainsString('Demo@corexweb.co.za', $html);
        $this->assertStringContainsString('Corex@mobiledemo', $html);
        $this->assertStringContainsString('Jane Smith', $html);
    }

    /**
     * The person asked for a login. A mail host that will not take our email
     * must not turn that into an error page — the reveal is the deliverable.
     */
    public function test_a_mail_failure_still_reveals_the_login(): void
    {
        Mail::shouldReceive('to')->twice()->andThrow(new \RuntimeException('SMTP down'));

        $this->post(route('mobile-app.demo'), $this->validPayload())
            ->assertRedirect(route('mobile-app').'#demo-login')
            ->assertSessionHas('mobile_demo_name', 'Jane Smith')
            ->assertSessionHas('mobile_demo_emailed', false);

        $enquiry = ContactRequest::sole();
        $this->assertNull($enquiry->emailed_at);

        $this->get(route('mobile-app'))
            ->assertSee('Corex@mobiledemo')
            ->assertSee('our mail server wouldn&rsquo;t take the email', false);
    }

    public function test_the_session_attribution_is_stored_on_the_enquiry(): void
    {
        Mail::fake();

        $this->get('/pricing?utm_source=google&utm_medium=cpc', ['referer' => 'https://www.google.com/'])->assertOk();

        $this->post(route('mobile-app.demo'), $this->validPayload(), ['referer' => config('app.url').'/mobile-app']);

        $enquiry = ContactRequest::sole();

        $this->assertSame('google', $enquiry->utm_source);
        $this->assertSame('Paid search', $enquiry->channel());
        $this->assertStringEndsWith('/mobile-app', $enquiry->page_url);
    }

    public function test_consent_is_required(): void
    {
        Mail::fake();

        $this->post(route('mobile-app.demo'), $this->validPayload(['consent' => null]))
            ->assertSessionHasErrors('consent');

        $this->assertSame(0, ContactRequest::count());
        Mail::assertNothingSent();
    }

    public function test_a_filled_honeypot_is_rejected_and_stores_nothing(): void
    {
        Mail::fake();

        $this->post(route('mobile-app.demo'), $this->validPayload(['website' => 'https://spam.example']))
            ->assertSessionHasErrors('website');

        $this->assertSame(0, ContactRequest::count());
        Mail::assertNothingSent();
    }

    /**
     * The demo topic is set by this controller, never chosen by a sender — so
     * the contact form cannot post it.
     */
    public function test_the_contact_form_cannot_claim_the_demo_topic(): void
    {
        Mail::fake();

        $this->post(route('contact.store'), [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'topic' => 'mobile-demo',
            'message' => 'Give me the login',
            'consent' => '1',
        ])->assertSessionHasErrors('topic');

        $this->assertSame(0, ContactRequest::count());
    }

    public function test_the_route_is_rate_limited(): void
    {
        // The array cache store persists across tests in the process, so start
        // from a known throttle count rather than inheriting one.
        Cache::flush();
        Mail::fake();

        for ($i = 0; $i < 6; $i++) {
            $this->post(route('mobile-app.demo'), $this->validPayload(['email' => "jane{$i}@example.com"]));
        }

        $this->post(route('mobile-app.demo'), $this->validPayload(['email' => 'one.too.many@example.com']))
            ->assertStatus(429);
    }
}
