<?php

namespace Tests\Feature\Admin;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as AssertInertia;
use Tests\TestCase;

class SiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create();
    }

    public function test_the_settings_screen_requires_a_session(): void
    {
        $this->get(route('admin.marketing.edit'))->assertRedirect(route('login'));
        $this->put(route('admin.marketing.update'), [])->assertRedirect(route('login'));
    }

    /**
     * The singleton row is created on first access rather than requiring a
     * migration seeder — see SiteSetting::current().
     */
    public function test_the_settings_row_is_created_automatically(): void
    {
        $this->assertSame(0, SiteSetting::count());

        $this->actingAs($this->admin())
            ->get(route('admin.marketing.edit'))
            ->assertOk()
            ->assertInertia(fn (AssertInertia $page) => $page->component('Admin/Marketing/Edit'));

        $this->assertSame(1, SiteSetting::count());
    }

    public function test_settings_can_be_saved(): void
    {
        $this->actingAs($this->admin())->put(route('admin.marketing.update'), [
            'site_name' => 'CoreX OS',
            'ga4_measurement_id' => 'G-ABC123',
            'gtm_container_id' => 'GTM-XYZ789',
            'google_search_console_verification' => 'abc123',
        ])->assertRedirect(route('admin.marketing.edit'));

        $settings = SiteSetting::current();
        $this->assertSame('CoreX OS', $settings->site_name);
        $this->assertSame('G-ABC123', $settings->ga4_measurement_id);
    }

    /**
     * The dashboard's integration checklist and the analytics tags injected
     * into the public layout both read straight from these fields — the
     * regression to guard against is a value going in on this screen and not
     * making it back out anywhere.
     */
    public function test_saved_analytics_ids_appear_on_the_public_layout(): void
    {
        SiteSetting::current()->update([
            'ga4_measurement_id' => 'G-ABC123',
            'gtm_container_id' => 'GTM-XYZ789',
            'google_search_console_verification' => 'verify-me',
        ]);

        $body = $this->get('/')->getContent();

        $this->assertStringContainsString('G-ABC123', $body);
        $this->assertStringContainsString('GTM-XYZ789', $body);
        $this->assertStringContainsString('verify-me', $body);
    }

    public function test_the_contact_recipient_email_must_be_a_valid_address(): void
    {
        $this->actingAs($this->admin())->put(route('admin.marketing.update'), [
            'contact_recipient_email' => 'not-an-email',
        ])->assertSessionHasErrors('contact_recipient_email');
    }
}
