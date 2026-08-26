<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * The admin console.
 *
 * Two things are being defended here. One is the login: this console renders
 * the only record webinar registrants exist in, and every route must refuse a
 * logged-out request. The other is that nothing it shows is ours — no webinar
 * and no registrant is stored here, so the console can never disagree with the
 * record that actually governs each person's demo access.
 */
class WebinarAdminTest extends TestCase
{
    use RefreshDatabase;

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
        ]);
    }

    private function admin(): User
    {
        return User::factory()->create(['email' => 'johan@corexweb.co.za']);
    }

    /**
     * @return array<string, mixed>
     */
    private function webinarRow(array $overrides = []): array
    {
        return array_merge([
            'slug' => self::SLUG,
            'title' => 'CoreX OS — a walkthrough',
            'starts_at' => '2026-09-10T14:00:00+02:00',
            'duration_minutes' => 60,
            'registration_open' => true,
            'status_label' => 'Open for registration',
            'demo_access_ends_at' => '2026-09-13T23:59:59+02:00',
            'registration_count' => 47,
            'registration_url' => 'https://corexos.co.za/webinars/'.self::SLUG,
            'archived' => false,
        ], $overrides);
    }

    // ── The architectural rule ──────────────────────────────────────

    /**
     * CoreX is the source of truth. A copy of a webinar here that drifted by a
     * day would hand registrants an access deadline different from the one
     * enforced — silently. So there is nowhere for a copy to live.
     */
    public function test_the_website_stores_no_webinar_or_registrant_data(): void
    {
        $this->assertFalse(Schema::hasTable('webinars'));
        $this->assertFalse(Schema::hasTable('webinar_registrations'));
        $this->assertFalse(Schema::hasTable('registrations'));
    }

    // ── Every admin route is behind the login ───────────────────────

    /**
     * @return array<string, array{string, string}>
     */
    public static function adminRoutes(): array
    {
        return [
            'list' => ['GET', '/admin/webinars'],
            'create' => ['GET', '/admin/webinars/create'],
            'store' => ['POST', '/admin/webinars'],
            'edit' => ['GET', '/admin/webinars/'.self::SLUG.'/edit'],
            'update' => ['PUT', '/admin/webinars/'.self::SLUG],
            'archive' => ['DELETE', '/admin/webinars/'.self::SLUG],
            'registrants' => ['GET', '/admin/webinars/'.self::SLUG.'/registrations'],
            'zoom csv' => ['GET', '/admin/webinars/'.self::SLUG.'/registrations/zoom.csv'],
            'full csv' => ['GET', '/admin/webinars/'.self::SLUG.'/registrations/full.csv'],
        ];
    }

    #[DataProvider('adminRoutes')]
    public function test_admin_routes_reject_a_logged_out_visitor(string $method, string $uri): void
    {
        Http::fake();

        $this->call($method, $uri)->assertRedirect(route('login'));

        // And nothing was asked of CoreX on the way to being turned away.
        Http::assertNothingSent();
    }

    // ── Login ───────────────────────────────────────────────────────

    public function test_an_admin_can_sign_in_and_out(): void
    {
        $user = User::factory()->create([
            'email' => 'johan@corexweb.co.za',
            'password' => 'a-long-enough-passphrase',
        ]);

        $this->post(route('login.store'), [
            'email' => 'johan@corexweb.co.za',
            'password' => 'a-long-enough-passphrase',
        ])->assertRedirect(route('admin.webinars.index'));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_a_wrong_password_is_refused_without_saying_which_part_was_wrong(): void
    {
        User::factory()->create(['email' => 'johan@corexweb.co.za', 'password' => 'a-long-enough-passphrase']);

        $this->post(route('login.store'), [
            'email' => 'johan@corexweb.co.za',
            'password' => 'wrong',
        ])->assertSessionHasErrors(['email' => 'Those details do not match an account.']);

        // The same message for an address that does not exist — telling them
        // apart would confirm which addresses are real.
        $this->post(route('login.store'), [
            'email' => 'nobody@example.com',
            'password' => 'wrong',
        ])->assertSessionHasErrors(['email' => 'Those details do not match an account.']);

        $this->assertGuest();
    }

    public function test_login_attempts_are_rate_limited(): void
    {
        User::factory()->create(['email' => 'johan@corexweb.co.za', 'password' => 'a-long-enough-passphrase']);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('login.store'), [
                'email' => 'johan@corexweb.co.za',
                'password' => 'wrong',
            ]);
        }

        $response = $this->post(route('login.store'), [
            'email' => 'johan@corexweb.co.za',
            'password' => 'a-long-enough-passphrase',
        ]);

        // Even the correct password is refused once the limit is hit.
        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString(
            'Too many login attempts',
            session('errors')->first('email'),
        );
        $this->assertGuest();
    }

    public function test_the_login_page_renders_and_offers_no_way_to_self_register(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Sign in')
            ->assertSee('there is no reset link by design')
            ->assertDontSee('Create an account');
    }

    public function test_the_create_form_renders_with_its_defaults(): void
    {
        Http::fake();

        $this->actingAs($this->admin())
            ->get(route('admin.webinars.create'))
            ->assertOk()
            ->assertSee('Link name')
            ->assertSee('Leave blank to build it from the title.')
            ->assertSee('Only people who register ever see this.')
            // The defaults the help text promises.
            ->assertSee('value="60"', false)
            ->assertSee('value="3"', false)
            ->assertSee('value="24"', false);

        // A blank form asks CoreX for nothing.
        Http::assertNothingSent();
    }

    public function test_the_old_admin_login_url_still_reaches_the_sign_in_page(): void
    {
        $this->get('/admin/login')->assertRedirect('/login');
    }

    /**
     * The seeded account is the one the team is handed, so it is worth proving
     * it can actually sign in rather than assuming the hash took.
     */
    public function test_the_seeded_admin_account_can_sign_in(): void
    {
        $this->seed(AdminUserSeeder::class);

        $this->post(route('login.store'), [
            'email' => 'andre@corexos.co.za',
            'password' => 'Mineme098@',
        ])->assertRedirect(route('admin.webinars.index'));

        $this->assertAuthenticated();

        // Re-running the seeder must not fail on the unique index.
        $this->seed(AdminUserSeeder::class);
        $this->assertSame(1, User::where('email', 'andre@corexos.co.za')->count());
    }

    public function test_there_is_no_self_registration_or_password_reset_route(): void
    {
        $this->get('/admin/register')->assertNotFound();
        $this->get('/admin/forgot-password')->assertNotFound();
        $this->get('/register')->assertNotFound();
        $this->get('/forgot-password')->assertNotFound();
    }

    // ── The webinar list ────────────────────────────────────────────

    public function test_the_list_renders_from_corex_using_the_admin_token(): void
    {
        Http::fake(['*/api/v1/webinars*' => Http::response([
            'ok' => true, 'webinars' => [$this->webinarRow()],
        ])]);

        $this->actingAs($this->admin())
            ->get(route('admin.webinars.index'))
            ->assertOk()
            ->assertSee('CoreX OS — a walkthrough', false)
            ->assertSee('Open for registration')
            ->assertSee('47')
            // The link this screen exists to hand out.
            ->assertSee('https://corexos.co.za/webinars/'.self::SLUG);

        Http::assertSent(function (Request $request) {
            $this->assertSame('Bearer '.self::ADMIN_TOKEN, $request->header('Authorization')[0]);

            return true;
        });
    }

    public function test_neither_token_reaches_the_browser_on_an_admin_page(): void
    {
        Http::fake(['*' => Http::response(['ok' => true, 'webinars' => [$this->webinarRow()]])]);

        $body = $this->actingAs($this->admin())
            ->get(route('admin.webinars.index'))
            ->getContent();

        $this->assertStringNotContainsString(self::ADMIN_TOKEN, $body);
        $this->assertStringNotContainsString(self::PUBLIC_TOKEN, $body);
        $this->assertStringNotContainsString('cx_site_', $body);
    }

    public function test_the_archived_toggle_asks_corex_for_archived_webinars(): void
    {
        Http::fake(['*' => Http::response(['ok' => true, 'webinars' => []])]);

        $this->actingAs($this->admin())
            ->get(route('admin.webinars.index', ['archived' => 1]))
            ->assertOk();

        Http::assertSent(fn (Request $request) => str_contains($request->url(), 'include_archived=true'));
    }

    // ── Create and edit ─────────────────────────────────────────────

    /**
     * The datetime-local input has no offset. Sending it bare, or as UTC, would
     * move every webinar two hours — and the demo-access deadline with it.
     */
    public function test_creating_a_webinar_sends_the_time_with_an_explicit_sast_offset(): void
    {
        Http::fake(['*' => Http::response(['ok' => true, 'webinar' => $this->webinarRow()], 201)]);

        $this->actingAs($this->admin())->post(route('admin.webinars.store'), [
            'title' => 'CoreX OS — a walkthrough',
            'slug' => '',
            'description' => 'Everything a principal needs to see.',
            'starts_at' => '2026-09-10T14:00',
            'duration_minutes' => '60',
            'join_url' => 'https://zoom.us/j/123456789',
            'access_ends_days_after' => '3',
            'reminder_hours_before' => '24',
        ])->assertRedirect(route('admin.webinars.index'));

        Http::assertSent(function (Request $request) {
            $this->assertSame('2026-09-10T14:00:00+02:00', $request->data()['starts_at']);
            $this->assertSame(60, $request->data()['duration_minutes']);
            $this->assertSame(3, $request->data()['access_ends_days_after']);

            return true;
        });
    }

    public function test_corex_validation_errors_render_against_their_own_fields(): void
    {
        Http::fake(['*' => Http::response([
            'ok' => false,
            'errors' => ['slug' => ['That link name is already taken.']],
        ], 422)]);

        $this->actingAs($this->admin())
            ->post(route('admin.webinars.store'), [
                'title' => 'Taken',
                'slug' => 'corex-walkthrough-sept',
                'starts_at' => '2026-09-10T14:00',
            ])
            ->assertSessionHasErrors(['slug' => 'That link name is already taken.']);
    }

    public function test_the_edit_screen_warns_that_existing_registrants_keep_their_end_date(): void
    {
        Http::fake([
            '*/api/v1/webinars/'.self::SLUG => Http::response([
                'ok' => true,
                'webinar' => $this->webinarRow(['description' => 'A walkthrough.', 'join_url' => 'https://zoom.us/j/1']),
            ]),
            '*/api/v1/webinars*' => Http::response(['ok' => true, 'webinars' => [$this->webinarRow()]]),
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.webinars.edit', self::SLUG))
            ->assertOk()
            ->assertSee('People have already registered.')
            ->assertSee('keep the end date they')
            // The datetime-local value is the SAST wall-clock time, not UTC.
            ->assertSee('2026-09-10T14:00', false);
    }

    /**
     * If CoreX cannot tell us the current joining link, the field is blank —
     * and saving a blank field must not wipe the link that is actually set.
     */
    public function test_a_field_corex_did_not_return_is_left_out_of_the_save(): void
    {
        Http::fake(['*' => Http::response(['ok' => true, 'webinar' => $this->webinarRow()])]);

        $this->actingAs($this->admin())->put(route('admin.webinars.update', self::SLUG), [
            'title' => 'CoreX OS — a walkthrough',
            'starts_at' => '2026-09-10T14:00',
            'join_url' => '',
            '_unknown' => ['join_url'],
        ]);

        Http::assertSent(function (Request $request) {
            if ($request->method() !== 'PUT') {
                return true;
            }

            $this->assertArrayNotHasKey(
                'join_url',
                $request->data(),
                'A joining link we could not read must not be overwritten with a blank.',
            );

            return true;
        });
    }

    public function test_archiving_calls_delete_on_corex(): void
    {
        Http::fake(['*' => Http::response(['ok' => true])]);

        $this->actingAs($this->admin())
            ->delete(route('admin.webinars.archive', self::SLUG))
            ->assertRedirect(route('admin.webinars.index'));

        Http::assertSent(fn (Request $request) => $request->method() === 'DELETE'
            && str_ends_with($request->url(), '/api/v1/webinars/'.self::SLUG));
    }

    // ── Registrants ─────────────────────────────────────────────────

    public function test_the_registrant_list_renders_behind_the_login(): void
    {
        Http::fake(['*/registrations*' => Http::response([
            'ok' => true,
            'webinar' => ['slug' => self::SLUG, 'title' => 'CoreX OS — a walkthrough', 'starts_at' => '2026-09-10T14:00:00+02:00'],
            'registrations' => [
                [
                    'id' => 91, 'first_name' => 'Jane', 'last_name' => 'Smith',
                    'email' => 'jane@acme.co.za', 'company_name' => 'Acme Properties',
                    'phone' => '+27 82 000 0000', 'registered_at' => '2026-08-28T09:14:22+02:00',
                    'demo_access_status' => 'Active', 'demo_access_ends_at' => '2026-09-13T23:59:59+02:00',
                    'reminder_sent_at' => null,
                ],
                [
                    'id' => 92, 'first_name' => 'Thabo', 'last_name' => 'Dlamini',
                    'email' => 'thabo@ridge.co.za', 'company_name' => 'Ridge Realty',
                    'phone' => null, 'registered_at' => '2026-08-29T11:02:00+02:00',
                    'demo_access_status' => 'Active', 'demo_access_ends_at' => '2026-09-13T23:59:59+02:00',
                    'reminder_sent_at' => '2026-09-09T14:00:00+02:00',
                ],
            ],
            'meta' => ['current_page' => 1, 'per_page' => 100, 'total' => 2, 'last_page' => 1],
        ])]);

        $response = $this->actingAs($this->admin())
            ->get(route('admin.webinars.registrations', self::SLUG))
            ->assertOk()
            ->assertSee('Jane Smith')
            ->assertSee('Acme Properties')
            ->assertSee('jane@acme.co.za')
            ->assertSee('+27 82 000 0000')
            ->assertSee('Active')
            ->assertSee('Not yet');

        // Newest first — the reason to open this screen is "who came in since
        // I last looked".
        $body = $response->getContent();
        $this->assertLessThan(
            strpos($body, 'Jane Smith'),
            strpos($body, 'Thabo Dlamini'),
        );
    }

    public function test_registrant_data_is_never_on_a_public_route(): void
    {
        Http::fake(['*' => Http::response(['ok' => true, 'webinar' => [
            'slug' => self::SLUG, 'title' => 'A walkthrough', 'description' => 'x',
            'starts_at' => '2026-09-10T14:00:00+02:00', 'duration_minutes' => 60,
            'registration_open' => true, 'demo_access_ends_at' => '2026-09-13T23:59:59+02:00',
        ]])]);

        $body = $this->get(route('webinars.show', self::SLUG))->getContent();

        // No registrant, and no route to one. The public page has no reason to
        // know that the admin console exists.
        //
        // Deliberately not the form's own placeholder address — that appears on
        // the page legitimately, and matching on it would make this pass or fail
        // for the wrong reason.
        $this->assertStringNotContainsString('thabo@ridge.co.za', $body);
        $this->assertStringNotContainsString('/admin/', $body);
        $this->assertStringNotContainsString('registrations.csv', $body);
    }

    // ── The downloads ───────────────────────────────────────────────

    /**
     * Byte-for-byte. The Zoom column order is what Zoom's importer expects and
     * CoreX is the one place that definition should live — reshaping it here
     * would put a second copy of it in the wrong repository.
     */
    public function test_the_zoom_csv_streams_through_untouched(): void
    {
        $csv = "Email Address,First Name,Last Name,Company\r\njane@acme.co.za,Jane,Smith,Acme Properties\r\n";

        Http::fake(['*/registrations.csv*' => Http::response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="corex-walkthrough-sept-zoom.csv"',
        ])]);

        $response = $this->actingAs($this->admin())
            ->get(route('admin.webinars.registrations.download', [self::SLUG, 'zoom']));

        $response->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->assertHeader('Content-Disposition', 'attachment; filename="corex-walkthrough-sept-zoom.csv"');

        $this->assertSame($csv, $response->streamedContent());

        Http::assertSent(fn (Request $request) => str_contains($request->url(), 'format=zoom'));
    }

    public function test_the_full_csv_streams_through_untouched(): void
    {
        $csv = "First Name,Last Name,Email,Company,Phone,Registered,Demo access,Access ends,Reminder sent\r\n";

        Http::fake(['*/registrations.csv*' => Http::response($csv, 200, ['Content-Type' => 'text/csv'])]);

        $response = $this->actingAs($this->admin())
            ->get(route('admin.webinars.registrations.download', [self::SLUG, 'full']));

        $response->assertOk();
        $this->assertSame($csv, $response->streamedContent());

        Http::assertSent(fn (Request $request) => str_contains($request->url(), 'format=full'));
    }

    public function test_an_unknown_csv_format_is_not_served(): void
    {
        Http::fake();

        $this->actingAs($this->admin())
            ->get('/admin/webinars/'.self::SLUG.'/registrations/everything.csv')
            ->assertNotFound();

        Http::assertNothingSent();
    }
}
