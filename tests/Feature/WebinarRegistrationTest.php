<?php

namespace Tests\Feature;

use App\Support\Sast;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * The public registration page.
 *
 * The rules being defended here are the ones whose failure is silent: a token
 * reaching the browser, a join link on a public page, a successful
 * registration shown to the visitor as an error.
 */
class WebinarRegistrationTest extends TestCase
{
    private const PUBLIC_TOKEN = 'cx_site_pubtest.public-secret';

    private const ADMIN_TOKEN = 'cx_site_admtest.admin-secret';

    private const SLUG = 'corex-walkthrough-sept';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'corex.base_url' => 'https://corexos.test',
            'corex.public_token' => self::PUBLIC_TOKEN,
            'corex.admin_token' => self::ADMIN_TOKEN,
            'corex.send_legacy_name' => true,
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function webinarPayload(array $overrides = []): array
    {
        return ['ok' => true, 'webinar' => array_merge([
            'slug' => self::SLUG,
            'title' => 'CoreX OS — a walkthrough for agency principals',
            'description' => 'Everything a principal needs to see, in 45 minutes.',
            'starts_at' => '2026-09-10T14:00:00+02:00',
            'duration_minutes' => 60,
            'registration_open' => true,
            'demo_access_ends_at' => '2026-09-13T23:59:59+02:00',
        ], $overrides)];
    }

    public function test_the_page_shows_the_webinar_in_south_african_time(): void
    {
        Http::fake(['*/api/v1/webinars/'.self::SLUG => Http::response($this->webinarPayload())]);

        $response = $this->get(route('webinars.show', self::SLUG));

        $response->assertOk()
            ->assertSee('CoreX OS — a walkthrough for agency principals', false)
            ->assertSee('Everything a principal needs to see')
            // 14:00 SAST, formatted rather than shifted. A UTC render would say 12:00.
            ->assertSee('Thursday, 10 September 2026')
            ->assertSee('14:00')
            ->assertSee('SAST');
    }

    public function test_the_public_page_never_leaks_a_token_or_a_join_link(): void
    {
        Http::fake(['*' => Http::response($this->webinarPayload())]);

        $body = $this->get(route('webinars.show', self::SLUG))->getContent();

        // The whole reason the browser posts to us instead of to CoreX.
        $this->assertStringNotContainsString(self::PUBLIC_TOKEN, $body);
        $this->assertStringNotContainsString(self::ADMIN_TOKEN, $body);
        $this->assertStringNotContainsString('cx_site_', $body);
        $this->assertStringNotContainsString('Authorization', $body);

        // The join link is earned by registering. The page must not show it and
        // must not go looking for it.
        $this->assertStringNotContainsString('zoom.us', $body);
        $this->assertStringNotContainsString('join_url', $body);
    }

    public function test_the_registration_call_uses_the_public_token_not_the_admin_one(): void
    {
        Http::fake(['*' => Http::response($this->webinarPayload())]);

        $this->get(route('webinars.show', self::SLUG));

        Http::assertSent(function (Request $request) {
            $this->assertSame(
                'Bearer '.self::PUBLIC_TOKEN,
                $request->header('Authorization')[0],
                'The public page must use the public token — a compromise of this path must not reach the registrant list.',
            );

            return true;
        });
    }

    public function test_a_closed_webinar_shows_a_plain_closed_state_not_an_error(): void
    {
        Http::fake(['*' => Http::response($this->webinarPayload(['registration_open' => false]))]);

        $this->get(route('webinars.show', self::SLUG))
            ->assertOk()
            ->assertSee('Registration has closed')
            ->assertDontSee('Save your seat');
    }

    public function test_an_unknown_webinar_shows_the_closed_state(): void
    {
        Http::fake(['*' => Http::response(['ok' => false, 'message' => 'Not open.'], 404)]);

        $this->get(route('webinars.show', 'no-such-webinar'))
            ->assertOk()
            ->assertSee('Registration has closed');
    }

    // ── The registration cut-off ────────────────────────────────────

    public function test_a_deadline_is_shown_on_the_registration_page(): void
    {
        // Built in SAST, not UTC — 17:00 UTC would render as 19:00 here and the
        // assertion would be testing the timezone rather than the deadline.
        Http::fake(['*' => Http::response($this->webinarPayload([
            'registration_closes_at' => CarbonImmutable::now(Sast::ZONE)
                ->addDays(3)->setTime(17, 0)->toIso8601String(),
        ]))]);

        $this->get(route('webinars.show', self::SLUG))
            ->assertOk()
            ->assertSee('Registration closes')
            ->assertSee('17:00')
            ->assertSee('Save your seat');
    }

    /**
     * The cut-off is the website's to enforce for now: CoreX still derives
     * registration_open from the START time, so a webinar past its deadline but
     * before its start still comes back open.
     */
    public function test_a_passed_deadline_closes_the_page_even_though_corex_says_open(): void
    {
        Http::fake(['*' => Http::response($this->webinarPayload([
            'registration_open' => true,
            'registration_closes_at' => now()->subHour()->toIso8601String(),
        ]))]);

        $this->get(route('webinars.show', self::SLUG))
            ->assertOk()
            ->assertSee('Registration has closed')
            ->assertSee(config('corex.contact_email'))
            ->assertDontSee('Save your seat');
    }

    /**
     * The form may have sat open in a tab past the deadline. Nothing must reach
     * CoreX — a late registration would mint a demo credential nobody meant to
     * issue.
     */
    public function test_a_submission_after_the_deadline_never_reaches_corex(): void
    {
        Http::fake(['*' => Http::response($this->webinarPayload([
            'registration_closes_at' => now()->subMinute()->toIso8601String(),
        ]))]);

        $this->post(route('webinars.register', self::SLUG), [
            'first_name' => 'Jan',
            'last_name' => 'Smith',
            'email' => 'jan@acme.co.za',
            'company_name' => 'Acme',
        ])->assertOk()->assertSee('Registration has closed');

        Http::assertNotSent(fn (Request $request) => $request->method() === 'POST');
    }

    public function test_a_deadline_still_in_the_future_lets_registration_through(): void
    {
        Http::fake([
            '*/register' => Http::response(['ok' => true, 'registered' => true, 'throttled' => false]),
            '*' => Http::response($this->webinarPayload([
                'registration_closes_at' => now()->addDay()->toIso8601String(),
            ])),
        ]);

        $this->post(route('webinars.register', self::SLUG), [
            'first_name' => 'Jan',
            'last_name' => 'Smith',
            'email' => 'jan@acme.co.za',
            'company_name' => 'Acme',
        ])->assertRedirect(route('webinars.thanks', self::SLUG));
    }

    public function test_the_closed_page_offers_the_website_address(): void
    {
        Http::fake(['*' => Http::response(['ok' => false, 'message' => 'Not open.'], 404)]);

        $this->get(route('webinars.show', self::SLUG))
            ->assertOk()
            ->assertSee('Still interested?')
            ->assertSee(config('corex.contact_email'));
    }

    public function test_a_successful_registration_lands_on_the_thank_you_page(): void
    {
        Http::fake(['*/register' => Http::response([
            'ok' => true, 'registered' => true, 'throttled' => false,
        ]), '*' => Http::response($this->webinarPayload())]);

        $this->post(route('webinars.register', self::SLUG), [
            'first_name' => 'Jan',
            'last_name' => 'van der Merwe',
            'email' => 'jan@acme.co.za',
            'company_name' => 'Acme Properties',
            'phone' => '+27 82 000 0000',
        ])->assertRedirect(route('webinars.thanks', self::SLUG));

        $this->followingRedirects()
            ->post(route('webinars.register', self::SLUG), [
                'first_name' => 'Jan',
                'last_name' => 'van der Merwe',
                'email' => 'jan@acme.co.za',
                'company_name' => 'Acme Properties',
            ])
            ->assertOk()
            ->assertSee('You&rsquo;re registered, Jan', false)
            // The one thing they must not miss: nobody can resend that code.
            ->assertSee('only copy of your demo access code');
    }

    /**
     * The one that is easiest to get wrong and worst to get wrong: CoreX says
     * "you already did this a few minutes ago", and the temptation is to show
     * it as a failure. The person is registered. It is a success page.
     */
    public function test_a_throttled_repeat_submit_is_shown_as_success(): void
    {
        Http::fake(['*/register' => Http::response([
            'ok' => true, 'registered' => true, 'throttled' => true,
        ]), '*' => Http::response($this->webinarPayload())]);

        $response = $this->followingRedirects()->post(route('webinars.register', self::SLUG), [
            'first_name' => 'Jan',
            'last_name' => 'van der Merwe',
            'email' => 'jan@acme.co.za',
            'company_name' => 'Acme Properties',
        ]);

        $response->assertOk()
            ->assertSee('You&rsquo;re already registered', false)
            ->assertSee('no need to register again');

        // Not a failure, not a warning, and nothing in the error bag.
        $response->assertDontSee('went wrong');
        $response->assertDontSee('did not go through');
        $this->assertEmpty(session('errors') ?? []);
    }

    public function test_corex_validation_errors_render_against_their_own_fields(): void
    {
        Http::fake(['*/register' => Http::response([
            'ok' => false,
            'errors' => [
                'email' => ['Please enter a valid email address.'],
                'company_name' => ['Please enter your company name.'],
            ],
        ], 422), '*' => Http::response($this->webinarPayload())]);

        $this->post(route('webinars.register', self::SLUG), [
            'first_name' => 'Jan',
            'last_name' => 'Smith',
            'email' => 'jan@acme.co.za',
            'company_name' => 'Acme',
        ])
            ->assertRedirect()
            // CoreX's own wording, against CoreX's own field keys.
            ->assertSessionHasErrors([
                'email' => 'Please enter a valid email address.',
                'company_name' => 'Please enter your company name.',
            ]);
    }

    /**
     * While CoreX still validates a single joined `name`, an error keyed `name`
     * has no input to attach to — it would vanish and the visitor would see a
     * form that silently refused to submit.
     */
    public function test_a_legacy_name_error_is_shown_against_the_first_name_field(): void
    {
        Http::fake(['*/register' => Http::response([
            'ok' => false,
            'errors' => ['name' => ['Please enter your name.']],
        ], 422), '*' => Http::response($this->webinarPayload())]);

        $this->post(route('webinars.register', self::SLUG), [
            'first_name' => 'J',
            'last_name' => 'Smith',
            'email' => 'jan@acme.co.za',
            'company_name' => 'Acme',
        ])->assertSessionHasErrors(['first_name' => 'Please enter your name.']);
    }

    public function test_the_name_is_sent_split_and_joined_during_the_changeover(): void
    {
        Http::fake(['*/register' => Http::response(['ok' => true, 'registered' => true, 'throttled' => false]), '*' => Http::response($this->webinarPayload())]);

        $this->post(route('webinars.register', self::SLUG), [
            'first_name' => 'Jan',
            'last_name' => 'van der Merwe',
            'email' => 'jan@acme.co.za',
            'company_name' => 'Acme Properties',
            'phone' => '+27 82 000 0000',
        ]);

        Http::assertSent(function (Request $request) {
            // The controller re-reads the webinar before writing, so skip that
            // GET and assert against the registration itself.
            if ($request->method() !== 'POST') {
                return true;
            }

            $body = $request->data();

            // Collected as two inputs from day one — splitting a full name later
            // would guess wrong on exactly this name.
            $this->assertSame('Jan', $body['first_name']);
            $this->assertSame('van der Merwe', $body['last_name']);

            // And the joined form, so today's CoreX validator is satisfied too.
            $this->assertSame('Jan van der Merwe', $body['name']);

            $this->assertSame('Acme Properties', $body['company_name']);

            return true;
        });
    }

    public function test_the_legacy_name_field_can_be_switched_off(): void
    {
        config(['corex.send_legacy_name' => false]);

        Http::fake(['*/register' => Http::response(['ok' => true, 'registered' => true, 'throttled' => false]), '*' => Http::response($this->webinarPayload())]);

        $this->post(route('webinars.register', self::SLUG), [
            'first_name' => 'Jan',
            'last_name' => 'Smith',
            'email' => 'jan@acme.co.za',
            'company_name' => 'Acme',
        ]);

        Http::assertSent(function (Request $request) {
            if ($request->method() !== 'POST') {
                return true;
            }

            $this->assertArrayNotHasKey('name', $request->data());

            return true;
        });
    }

    public function test_a_registration_that_closed_in_the_meantime_shows_the_closed_state(): void
    {
        Http::fake(['*/register' => Http::response(['ok' => false, 'message' => 'Not open.'], 404), '*' => Http::response($this->webinarPayload())]);

        $this->post(route('webinars.register', self::SLUG), [
            'first_name' => 'Jan',
            'last_name' => 'Smith',
            'email' => 'jan@acme.co.za',
            'company_name' => 'Acme',
        ])->assertOk()->assertSee('Registration has closed');
    }

    /**
     * A 401 is our configuration fault, not the visitor's mistake. They get an
     * apology, never a validation message implying they typed something wrong.
     */
    public function test_a_rejected_token_shows_a_generic_apology(): void
    {
        Http::fake([
            '*/register' => Http::response(['message' => 'Unauthenticated.'], 401),
            // The redirect lands back on the form, which fetches the webinar again.
            '*' => Http::response($this->webinarPayload()),
        ]);

        $this->followingRedirects()
            ->post(route('webinars.register', self::SLUG), [
                'first_name' => 'Jan',
                'last_name' => 'Smith',
                'email' => 'jan@acme.co.za',
                'company_name' => 'Acme',
            ])
            ->assertOk()
            ->assertSee('Something went wrong on our side');
    }

    public function test_the_thank_you_page_is_not_reachable_without_registering(): void
    {
        Http::fake(['*' => Http::response($this->webinarPayload())]);

        $this->get(route('webinars.thanks', self::SLUG))
            ->assertRedirect(route('webinars.show', self::SLUG));
    }

    /**
     * CoreX sends exactly one email, and it is the one that carries the demo
     * credentials. A second from us would mean two emails about one signup, and
     * ours would be the one without the thing they actually need.
     */
    public function test_the_website_sends_no_email_of_its_own(): void
    {
        Mail::fake();
        Http::fake(['*/register' => Http::response(['ok' => true, 'registered' => true, 'throttled' => false]), '*' => Http::response($this->webinarPayload())]);

        $this->post(route('webinars.register', self::SLUG), [
            'first_name' => 'Jan',
            'last_name' => 'Smith',
            'email' => 'jan@acme.co.za',
            'company_name' => 'Acme',
        ]);

        Mail::assertNothingSent();
        Mail::assertNothingQueued();
    }

    public function test_the_form_requires_a_company(): void
    {
        Http::fake();

        $this->post(route('webinars.register', self::SLUG), [
            'first_name' => 'Jan',
            'last_name' => 'Smith',
            'email' => 'jan@acme.co.za',
        ])->assertSessionHasErrors('company_name');

        // Nothing should have gone to CoreX for a form this incomplete.
        Http::assertNothingSent();
    }
}
