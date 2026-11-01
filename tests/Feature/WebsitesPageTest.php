<?php

namespace Tests\Feature;

use App\Support\WebsiteShowcase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class WebsitesPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_websites_page_renders_with_its_seo_metadata(): void
    {
        // Routes register before the test database exists, so here the page
        // answers at its key; live, routes/web.php reads this slug.
        $this->assertDatabaseHas('pages', ['key' => 'websites', 'slug' => 'real-estate-website-templates', 'robots_index' => true]);

        $this->get(route('websites'))
            ->assertOk()
            ->assertSee('<title>Real Estate Website Design, Free With CoreX | CoreX OS</title>', false)
            ->assertSee('Live real estate')
            ->assertSee('We can design anything')
            ->assertSee('These templates are only examples.');
    }

    public function test_live_sites_are_listed_above_the_templates(): void
    {
        $html = $this->get(route('websites'))->assertOk()->getContent();

        $this->assertStringContainsString('href="https://hfcoastal.co.za/"', $html);
        $this->assertLessThan(strpos($html, 'id="templates"'), strpos($html, 'id="live"'));
    }

    public function test_every_template_is_framed_on_the_page_and_previews_full_size(): void
    {
        $html = $this->get(route('websites'))->assertOk()->getContent();

        $this->assertCount(6, WebsiteShowcase::templates());

        foreach (WebsiteShowcase::templates() as $key => $template) {
            $this->assertStringContainsString(e($template['name']), $html);
            $this->assertStringContainsString(e(route('websites.template', [$key, 'frame' => 1])), $html);

            $this->get(route('websites.template', $key))
                ->assertOk()
                ->assertSee('<meta name="robots" content="noindex, follow">', false)
                ->assertSee('Example template:')
                ->assertSee(e($template['name']), false);
        }

        // Framed, the bar along the top is left out.
        $this->get(route('websites.template', ['luxury', 'frame' => 1]))->assertOk()->assertDontSee('Example template:');
    }

    public function test_an_unknown_template_is_not_found(): void
    {
        $this->get(route('websites').'/nope')->assertNotFound();
    }

    public function test_the_page_is_in_the_sitemap_and_linked_from_every_page(): void
    {
        $this->get('/sitemap.xml')->assertSee('/real-estate-website-templates</loc>', false);

        // Header (desktop and mobile menus) as well as the footer.
        $this->assertSame(3, substr_count($this->get('/pricing')->getContent(), 'href="'.route('websites').'"'));
    }

    public function test_the_migration_never_overwrites_an_existing_row(): void
    {
        DB::table('pages')->where('key', 'websites')->update(['meta_title' => 'An admin wrote this']);

        (require database_path('migrations/2026_11_01_090000_add_websites_page.php'))->up();

        $this->assertSame(1, DB::table('pages')->where('key', 'websites')->count());
        $this->assertDatabaseHas('pages', ['key' => 'websites', 'meta_title' => 'An admin wrote this']);
    }
}
