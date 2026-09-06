<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as AssertInertia;
use Tests\TestCase;

class SeoEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_sitemap_lists_active_indexable_pages(): void
    {
        Page::factory()->create(['key' => 'home', 'slug' => 'home', 'is_active' => true, 'robots_index' => true]);
        Page::factory()->create(['key' => 'pricing', 'slug' => 'pricing', 'is_active' => true, 'robots_index' => true]);
        Page::factory()->create(['key' => 'draft', 'slug' => 'draft', 'is_active' => false, 'robots_index' => true]);
        Page::factory()->create(['key' => 'hidden', 'slug' => 'hidden', 'is_active' => true, 'robots_index' => false]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk()->assertHeader('Content-Type', 'application/xml');

        $body = $response->getContent();
        $this->assertStringContainsString('<loc>'.url('/').'</loc>', $body);
        $this->assertStringContainsString('<loc>'.url('/pricing').'</loc>', $body);
        $this->assertStringNotContainsString('draft', $body);
        $this->assertStringNotContainsString('hidden', $body);
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
        $response = $this->get('/robots.txt');

        $response->assertOk()
            ->assertSee('Disallow: /admin')
            ->assertSee(url('/sitemap.xml'), false);
    }

    public function test_the_admin_sitemap_overview_requires_a_session(): void
    {
        $this->get(route('admin.sitemap'))->assertRedirect(route('login'));
    }

    public function test_the_admin_sitemap_overview_renders(): void
    {
        Page::factory()->create(['key' => 'home', 'slug' => 'home']);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.sitemap'))
            ->assertOk()
            ->assertInertia(fn (AssertInertia $page) => $page
                ->component('Admin/Sitemap/Index')
                ->has('sections', 1)
                ->where('sections.0.url_count', 1)
            );
    }
}
