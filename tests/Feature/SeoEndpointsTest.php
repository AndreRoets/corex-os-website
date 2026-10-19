<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\User;
use App\Support\Pricing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as AssertInertia;
use Tests\TestCase;

class SeoEndpointsTest extends TestCase
{
    use RefreshDatabase;

    private const SITE = 'https://corexweb.co.za';

    protected function setUp(): void
    {
        parent::setUp();

        // The live host, so every "built from APP_URL, not the request" claim
        // below is tested against a request that arrives somewhere else.
        config([
            'app.url' => self::SITE,
            'seo.noindex_foreign_hosts.production_only' => false,
        ]);
    }

    // ── Sitemap ──────────────────────────────────────────────────────

    public function test_the_sitemap_lists_active_indexable_pages(): void
    {
        Page::factory()->create(['key' => 'home', 'slug' => 'home', 'is_active' => true, 'robots_index' => true]);
        Page::factory()->create(['key' => 'pricing', 'slug' => 'pricing', 'is_active' => true, 'robots_index' => true]);
        Page::factory()->create(['key' => 'draft', 'slug' => 'draft', 'is_active' => false, 'robots_index' => true]);
        Page::factory()->create(['key' => 'hidden', 'slug' => 'hidden', 'is_active' => true, 'robots_index' => false]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk()->assertHeader('Content-Type', 'application/xml');

        $body = $response->getContent();
        $this->assertStringContainsString('<loc>'.self::SITE.'</loc>', $body);
        $this->assertStringContainsString('<loc>'.self::SITE.'/pricing</loc>', $body);
        $this->assertStringNotContainsString('draft', $body);
        $this->assertStringNotContainsString('hidden', $body);
    }

    public function test_the_sitemap_names_the_configured_host_whatever_host_it_is_fetched_on(): void
    {
        Page::factory()->create(['key' => 'pricing', 'slug' => 'pricing']);

        $body = $this->get('http://91.99.130.85:8095/sitemap.xml')->assertOk()->getContent();

        $this->assertStringContainsString('<loc>'.self::SITE.'/pricing</loc>', $body);
        $this->assertStringNotContainsString('91.99.130.85', $body);
    }

    public function test_each_sitemap_entry_carries_its_page_last_modified_date(): void
    {
        Page::factory()->create(['key' => 'pricing', 'slug' => 'pricing', 'updated_at' => '2026-10-01 09:30:00']);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('<loc>'.self::SITE.'/pricing</loc>', false)
            ->assertSee('<lastmod>2026-10-01T09:30:00+00:00</lastmod>', false);
    }

    public function test_the_take_on_page_is_in_the_sitemap_without_a_reseed(): void
    {
        // Its row comes from a migration — the live site never ran PageSeeder.
        $this->assertDatabaseHas('pages', ['key' => 'take-on', 'slug' => 'take-on', 'is_active' => true]);

        $this->get('/sitemap.xml')->assertSee('<loc>'.self::SITE.'/take-on</loc>', false);
    }

    // ── robots.txt ──────────────────────────────────────────────────

    public function test_robots_txt_is_served_by_the_application_not_a_static_file(): void
    {
        $this->assertFileDoesNotExist(public_path('robots.txt'));
    }

    public function test_robots_txt_uses_the_configured_body(): void
    {
        SiteSetting::current()->update(['robots_txt' => "User-agent: *\nDisallow: /secret\n"]);

        $response = $this->get('/robots.txt');

        $response->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Disallow: /secret');
    }

    public function test_robots_txt_falls_back_to_a_sensible_default(): void
    {
        $body = $this->get('http://www.corexweb.co.za/robots.txt')->assertOk()->getContent();

        $this->assertSame(
            "User-agent: *\nDisallow: /admin\nDisallow: /login\n\nSitemap: ".self::SITE."/sitemap.xml\n",
            $body,
        );
    }

    // ── Duplicate hosts ─────────────────────────────────────────────

    public function test_a_request_on_another_host_is_marked_noindex_and_points_home(): void
    {
        Page::factory()->create(['key' => 'pricing', 'slug' => 'pricing']);

        foreach (['http://www.corexweb.co.za/pricing', 'http://91.99.130.85:8095/pricing'] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
                ->assertSee('<link rel="canonical" href="'.self::SITE.'/pricing">', false)
                ->assertSee('<meta property="og:url" content="'.self::SITE.'/pricing">', false);
        }
    }

    public function test_the_noindex_header_covers_responses_outside_the_page_routes(): void
    {
        $this->get('http://www.corexweb.co.za/robots.txt')->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->get('http://www.corexweb.co.za/no-such-page')->assertNotFound()->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_the_configured_host_is_not_marked_noindex(): void
    {
        $this->get(self::SITE.'/pricing')->assertOk()->assertHeaderMissing('X-Robots-Tag');
    }

    public function test_the_noindex_header_is_production_only_by_default(): void
    {
        config(['seo.noindex_foreign_hosts.production_only' => true]);

        // The test environment is not production, so another host passes.
        $this->get('http://www.corexweb.co.za/pricing')->assertOk()->assertHeaderMissing('X-Robots-Tag');
    }

    // ── Canonical and og:url ────────────────────────────────────────

    public function test_the_canonical_drops_the_query_string_and_trailing_slash(): void
    {
        Page::factory()->create(['key' => 'pricing', 'slug' => 'pricing']);

        $this->get('/pricing/?utm_source=newsletter&gclid=abc')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.self::SITE.'/pricing">', false)
            ->assertSee('<meta property="og:url" content="'.self::SITE.'/pricing">', false)
            ->assertDontSee('utm_source', false);
    }

    public function test_the_home_page_canonical_is_the_bare_site_root(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.self::SITE.'">', false);
    }

    public function test_a_canonical_set_in_the_admin_panel_wins(): void
    {
        Page::factory()->create(['key' => 'pricing', 'slug' => 'pricing', 'canonical_url' => 'https://corexweb.co.za/pricing-2026']);

        $this->get('/pricing')
            ->assertSee('<link rel="canonical" href="https://corexweb.co.za/pricing-2026">', false)
            ->assertSee('<meta property="og:url" content="https://corexweb.co.za/pricing-2026">', false);
    }

    public function test_index_php_urls_redirect_permanently_to_the_clean_path(): void
    {
        $this->get('/index.php/pricing?utm_source=x&page=2')->assertStatus(301)->assertRedirect('/pricing?utm_source=x&page=2');
        $this->get('/index.php')->assertStatus(301)->assertRedirect('/');
        $this->get('/index.php/')->assertStatus(301)->assertRedirect('/');
    }

    public function test_only_the_whole_index_php_segment_is_redirected(): void
    {
        $this->get('/index.phpx')->assertNotFound();
    }

    public function test_a_form_post_to_an_index_php_url_is_not_redirected(): void
    {
        $this->post('/index.php/contact')->assertStatus(404);
    }

    // ── Share image ─────────────────────────────────────────────────

    public function test_the_default_share_card_is_a_1200_by_630_png(): void
    {
        $this->assertFileExists(public_path('images/og-default.png'));

        [$width, $height, $type] = getimagesize(public_path('images/og-default.png'));

        $this->assertSame([1200, 630, IMAGETYPE_PNG], [$width, $height, $type]);
    }

    public function test_pages_without_their_own_image_share_the_default_card(): void
    {
        Page::factory()->create(['key' => 'pricing', 'slug' => 'pricing', 'og_title' => 'CoreX pricing', 'og_description' => 'Bands from R450.']);

        $this->get('/pricing')
            ->assertSee('<meta property="og:image" content="'.self::SITE.'/images/og-default.png">', false)
            ->assertSee('<meta property="og:image:width" content="1200">', false)
            ->assertSee('<meta property="og:image:height" content="630">', false)
            ->assertSee('<meta property="og:image:alt" content="CoreX OS — The Real Estate Operating System">', false)
            // Twitter falls back to the Open Graph values the row left empty.
            ->assertSee('<meta name="twitter:title" content="CoreX pricing">', false)
            ->assertSee('<meta name="twitter:description" content="Bands from R450.">', false)
            ->assertSee('<meta name="twitter:image" content="'.self::SITE.'/images/og-default.png">', false)
            ->assertDontSee('corex-mark.png">', false);
    }

    public function test_a_page_own_share_image_and_twitter_fields_win(): void
    {
        Page::factory()->create([
            'key' => 'pricing',
            'slug' => 'pricing',
            'og_image' => 'https://cdn.example/pricing.png',
            'twitter_title' => 'Twitter-only title',
            'twitter_image' => 'https://cdn.example/pricing-twitter.png',
        ]);

        $this->get('/pricing')
            ->assertSee('<meta property="og:image" content="https://cdn.example/pricing.png">', false)
            // Its size is unknown, so none is claimed.
            ->assertDontSee('og:image:width', false)
            ->assertSee('<meta name="twitter:title" content="Twitter-only title">', false)
            ->assertSee('<meta name="twitter:image" content="https://cdn.example/pricing-twitter.png">', false);
    }

    // ── Structured data ─────────────────────────────────────────────

    public function test_the_structured_data_is_valid_json_with_the_published_price_range(): void
    {
        $graph = $this->graphTypes($this->get('/')->assertOk()->getContent());

        $software = $graph['SoftwareApplication'];
        $this->assertSame('BusinessApplication', $software['applicationCategory']);
        $this->assertSame('Web, Android, iOS', $software['operatingSystem']);
        $this->assertSame('AggregateOffer', $software['offers']['@type']);
        $this->assertSame('ZAR', $software['offers']['priceCurrency']);
        $this->assertSame(450, $software['offers']['lowPrice']);
        $this->assertSame(5095, $software['offers']['highPrice']);
        // And those are the price list's numbers, not copies of them.
        $this->assertSame(Pricing::lowestMonthly(), $software['offers']['lowPrice']);
        $this->assertSame(Pricing::highestMonthly(), $software['offers']['highPrice']);

        $organization = $graph['Organization'];
        $this->assertSame('CoreX OS', $organization['name']);
        $this->assertSame(self::SITE, $organization['url']);
        $this->assertSame('info@corexweb.co.za', $organization['email']);
        $this->assertSame('South Africa', $organization['areaServed']);
    }

    public function test_the_home_page_declares_the_website_and_no_breadcrumb(): void
    {
        $graph = $this->graphTypes($this->get('/')->getContent());

        $this->assertSame(self::SITE, $graph['WebSite']['url']);
        $this->assertArrayNotHasKey('BreadcrumbList', $graph);
    }

    public function test_other_pages_carry_a_breadcrumb_and_no_website(): void
    {
        Page::factory()->create(['key' => 'pricing', 'slug' => 'pricing', 'name' => 'Pricing']);

        $graph = $this->graphTypes($this->get('/pricing')->getContent());

        $this->assertArrayNotHasKey('WebSite', $graph);
        $this->assertSame([
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => self::SITE],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Pricing', 'item' => self::SITE.'/pricing'],
        ], $graph['BreadcrumbList']['itemListElement']);

        // The pricing page shows no questions, so it may not claim an FAQPage.
        $this->assertArrayNotHasKey('FAQPage', $graph);
    }

    public function test_a_page_name_cannot_close_the_script_element(): void
    {
        Page::factory()->create(['key' => 'pricing', 'slug' => 'pricing', 'name' => '</script><script>alert(1)</script>']);

        $html = $this->get('/pricing')->assertOk()->getContent();

        $this->assertStringNotContainsString('<script>alert(1)', $html);

        $graph = $this->graphTypes($html);
        $this->assertSame('</script><script>alert(1)</script>', $graph['BreadcrumbList']['itemListElement'][1]['name']);
    }

    public function test_admin_json_ld_is_still_printed_after_the_generated_graph(): void
    {
        Page::factory()->create(['key' => 'pricing', 'slug' => 'pricing', 'json_ld' => '{"@context":"https://schema.org","@type":"Event","name":"Launch"}']);

        $this->get('/pricing')->assertSeeInOrder([
            '"@graph"',
            '<script type="application/ld+json">{"@context":"https://schema.org","@type":"Event","name":"Launch"}</script>',
        ], false);
    }

    // ── Language and headings ───────────────────────────────────────

    public function test_pages_declare_south_african_english(): void
    {
        $this->get('/')->assertSee('<html lang="en-ZA"', false);
    }

    public function test_the_contact_page_has_exactly_one_h1(): void
    {
        $html = $this->get(route('contact'))->assertOk()->getContent();

        $this->assertSame(1, preg_match_all('/<h1[\s>]/', $html));
        $this->assertMatchesRegularExpression('/<h1 class="[^"]*text-3xl[^"]*">\s*Talk to the <span class="text-gradient">CoreX OS team\.<\/span>\s*<\/h1>/', $html);
    }

    // ── Metadata migration ──────────────────────────────────────────

    public function test_the_metadata_migration_only_replaces_untouched_seeded_defaults(): void
    {
        DB::table('pages')->insert([
            ['key' => 'home', 'name' => 'Home', 'slug' => 'home', 'meta_title' => 'CoreX OS — The Real Estate Operating System', 'meta_description' => 'Our own words.'],
            ['key' => 'contact', 'name' => 'Contact', 'slug' => 'contact', 'meta_title' => 'Say hello', 'meta_description' => 'Get in touch with the CoreX OS team — questions, support, or anything else.'],
        ]);

        $this->metadataMigration()->up();

        // Field by field: a default is replaced, an admin's edit next to it is not.
        $this->assertDatabaseHas('pages', ['key' => 'home', 'meta_title' => 'Real Estate Software South Africa | CoreX OS', 'meta_description' => 'Our own words.']);
        $this->assertDatabaseHas('pages', ['key' => 'contact', 'meta_title' => 'Say hello']);
        $this->assertSame(
            'Talk to the CoreX OS team about real estate software for your South African agency: a demo, pricing, support or moving from your current CRM.',
            DB::table('pages')->where('key', 'contact')->value('meta_description'),
        );
    }

    public function test_every_new_title_and_description_fits_in_a_search_result(): void
    {
        $changes = (new \ReflectionClassConstant($this->metadataMigration(), 'CHANGES'))->getValue();

        foreach ($changes as $key => $fields) {
            $this->assertLessThanOrEqual(60, mb_strlen($fields['meta_title'][1]), "{$key} title");
            $this->assertLessThanOrEqual(155, mb_strlen($fields['meta_description'][1]), "{$key} description");
        }
    }

    // ── Admin overview ──────────────────────────────────────────────

    public function test_the_admin_sitemap_overview_requires_a_session(): void
    {
        $this->get(route('admin.sitemap'))->assertRedirect(route('login'));
    }

    public function test_the_admin_sitemap_overview_renders(): void
    {
        Page::factory()->create(['key' => 'home', 'slug' => 'home']);

        // home, plus the features and take-on rows their migrations insert.
        $this->actingAs(User::factory()->create())
            ->get(route('admin.sitemap'))
            ->assertOk()
            ->assertInertia(fn (AssertInertia $page) => $page
                ->component('Admin/Sitemap/Index')
                ->has('sections', 1)
                ->where('sections.0.url_count', 3)
            );
    }

    /**
     * The generated graph's nodes, keyed by @type. Fails the test if the block
     * is missing or is not valid JSON.
     *
     * @return array<string, array<string, mixed>>
     */
    private function graphTypes(string $html): array
    {
        $this->assertSame(1, preg_match('#<script type="application/ld\+json">(\{"@context":"https://schema.org","@graph".*?)</script>#s', $html, $match));

        $data = json_decode($match[1], true, flags: JSON_THROW_ON_ERROR);

        return collect($data['@graph'])->keyBy('@type')->all();
    }

    private function metadataMigration(): object
    {
        return require database_path('migrations/2026_10_19_110000_update_page_metadata_for_search.php');
    }
}
