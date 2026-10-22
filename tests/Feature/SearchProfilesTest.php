<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SearchProfilesTest extends TestCase
{
    use RefreshDatabase;

    private const SITE = 'https://corexweb.co.za';

    private const KEY = 'a1b2c3d4e5f6a7b8';

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.url' => self::SITE]);
    }

    private function graph(string $path = '/'): array
    {
        $html = $this->get($path)->assertOk()->getContent();
        preg_match('#<script type="application/ld\+json">(\{"@context":"https://schema.org","@graph".*?)</script>#s', $html, $match);

        return collect(json_decode($match[1], true, flags: JSON_THROW_ON_ERROR)['@graph'])->keyBy('@type')->all();
    }

    // ── Bing verification ────────────────────────────────────────────

    public function test_the_bing_verification_tag_is_printed_once_set(): void
    {
        $this->get('/')->assertDontSee('msvalidate.01', false);

        SiteSetting::current()->update(['bing_site_verification' => 'ABC123']);

        $this->get('/')->assertSee('<meta name="msvalidate.01" content="ABC123">', false);
    }

    // ── sameAs ───────────────────────────────────────────────────────

    public function test_organization_profiles_come_from_the_admin_field_and_junk_is_dropped(): void
    {
        $this->assertArrayNotHasKey('sameAs', $this->graph()['Organization']);

        SiteSetting::current()->update(['same_as' => "https://www.linkedin.com/company/corex-os\n\n  not a url \njavascript:alert(1)\nhttps://www.linkedin.com/company/corex-os\r\nhttps://www.capterra.co.za/software/1/corex-os"]);

        $this->assertSame(
            ['https://www.linkedin.com/company/corex-os', 'https://www.capterra.co.za/software/1/corex-os'],
            $this->graph()['Organization']['sameAs'],
        );
    }

    public function test_the_software_links_its_app_store_listings(): void
    {
        $software = $this->graph()['SoftwareApplication'];

        $this->assertSame([config('corex.mobile_app.android_url'), config('corex.mobile_app.ios_url')], $software['sameAs']);
        $this->assertSame('CoreX', $software['alternateName']);
    }

    public function test_an_admin_can_save_both_new_settings(): void
    {
        $this->actingAs(User::factory()->create())
            ->put(route('admin.marketing.update'), ['bing_site_verification' => 'XYZ', 'same_as' => 'https://example.com/corex'])
            ->assertRedirect(route('admin.marketing.edit'));

        $this->assertDatabaseHas('site_settings', ['bing_site_verification' => 'XYZ', 'same_as' => 'https://example.com/corex']);
    }

    // ── IndexNow ─────────────────────────────────────────────────────

    public function test_the_key_file_is_not_served_without_a_key(): void
    {
        $this->get('/'.self::KEY.'.txt')->assertNotFound();
        $this->artisan('seo:indexnow')->assertFailed();
    }

    public function test_the_command_submits_every_sitemap_url_on_the_canonical_host(): void
    {
        config(['seo.indexnow.key' => self::KEY]);
        Http::fake(['api.indexnow.org/*' => Http::response(null, 200)]);

        $this->artisan('seo:indexnow')->assertSuccessful();

        Http::assertSent(function (Request $request) {
            return $request->url() === 'https://api.indexnow.org/indexnow'
                && $request['host'] === 'corexweb.co.za'
                && $request['key'] === self::KEY
                && $request['keyLocation'] === self::SITE.'/'.self::KEY.'.txt'
                && in_array(self::SITE.'/rental-management-software', $request['urlList'], true)
                && in_array(self::SITE.'/take-on', $request['urlList'], true);
        });
    }

    public function test_a_rejected_submission_fails_the_command(): void
    {
        config(['seo.indexnow.key' => self::KEY]);
        Http::fake(['api.indexnow.org/*' => Http::response(null, 422)]);

        $this->artisan('seo:indexnow')->assertFailed();
    }

    public function test_saving_an_indexable_page_in_admin_submits_it(): void
    {
        config(['seo.indexnow.key' => self::KEY]);
        Http::fake(['api.indexnow.org/*' => Http::response(null, 200)]);

        $page = Page::where('key', 'rentals')->firstOrFail();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.pages.update', $page), array_merge($page->only($page->getFillable()), ['meta_title' => 'New title']))
            ->assertSessionHasNoErrors();

        Http::assertSent(fn (Request $request) => $request['urlList'] === [self::SITE.'/rental-management-software']);
    }
}
