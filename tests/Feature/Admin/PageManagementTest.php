<?php

namespace Tests\Feature\Admin;

use App\Models\Page;
use App\Models\PageRedirect;
use App\Models\User;
use App\Support\PageRoutes;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as AssertInertia;
use Tests\TestCase;

class PageManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create();
    }

    public function test_the_index_and_edit_routes_require_a_session(): void
    {
        $page = Page::factory()->create();

        $this->get(route('admin.pages.index'))->assertRedirect(route('login'));
        $this->get(route('admin.pages.edit', $page))->assertRedirect(route('login'));
        $this->put(route('admin.pages.update', $page), [])->assertRedirect(route('login'));
    }

    public function test_the_index_lists_every_page(): void
    {
        $this->seed(PageSeeder::class);

        $this->actingAs($this->admin())
            ->get(route('admin.pages.index'))
            ->assertOk()
            ->assertInertia(fn (AssertInertia $page) => $page
                ->component('Admin/Pages/Index')
                ->has('pages', 5)
            );
    }

    public function test_updating_seo_metadata(): void
    {
        $page = Page::factory()->create(['key' => 'pricing', 'slug' => 'pricing', 'name' => 'Pricing']);

        $this->actingAs($this->admin())->put(route('admin.pages.update', $page), [
            'name' => 'Pricing',
            'slug' => 'pricing',
            'is_active' => true,
            'meta_title' => 'Custom title',
            'meta_description' => 'Custom description',
            'robots_index' => true,
            'robots_follow' => true,
            'og_type' => 'website',
            'twitter_card' => 'summary_large_image',
            'sitemap_priority' => '0.8',
            'sitemap_frequency' => 'monthly',
        ])->assertRedirect(route('admin.pages.edit', $page));

        $this->assertSame('Custom title', $page->fresh()->meta_title);
    }

    /**
     * The point of a stable `key`: renaming the slug changes the live URL,
     * but the admin still finds the same page by its key afterward, and a
     * record is left behind so the old address keeps working.
     */
    public function test_renaming_a_slug_leaves_a_redirect_record_behind(): void
    {
        $page = Page::factory()->create(['key' => 'pricing', 'slug' => 'pricing', 'name' => 'Pricing']);

        $this->actingAs($this->admin())->put(route('admin.pages.update', $page), [
            'name' => 'Pricing',
            'slug' => 'plans',
            'is_active' => true,
            'robots_index' => true,
            'robots_follow' => true,
            'og_type' => 'website',
            'twitter_card' => 'summary_large_image',
            'sitemap_priority' => '0.5',
            'sitemap_frequency' => 'weekly',
        ])->assertRedirect();

        $page->refresh();
        $this->assertSame('plans', $page->slug);

        $redirect = PageRedirect::where('page_id', $page->id)->first();
        $this->assertNotNull($redirect);
        $this->assertSame('pricing', $redirect->old_slug);
        $this->assertSame(301, $redirect->status_code);
    }

    /**
     * routes/web.php registers the public page and redirect routes once, at
     * request-boot time, by reading this table — so a rename saved mid-test
     * cannot show up in the same test's later HTTP calls (the route table a
     * Laravel test boots is fixed for that test's whole lifetime). That is
     * a testing-harness limit, not a production one: a real deployment boots
     * fresh on every request. What that registration loop actually computes
     * — the path a redirect should send a visitor to — is
     * App\Support\PageRoutes::pathFor(), covered directly in
     * tests/Unit/PageRoutesTest.php.
     */
    public function test_a_redirect_targets_the_pages_current_path(): void
    {
        $page = Page::factory()->create(['key' => 'pricing', 'slug' => 'plans']);
        $redirect = PageRedirect::create(['old_slug' => 'pricing', 'page_id' => $page->id, 'status_code' => 301]);

        $this->assertSame('/plans', PageRoutes::pathFor($redirect->page->key, $redirect->page->slug));
    }

    /**
     * A page can never be renamed onto a slug a hardcoded route already
     * owns — see App\Support\PageRoutes::reservedSlugs().
     */
    public function test_a_page_cannot_be_renamed_onto_a_reserved_slug(): void
    {
        $page = Page::factory()->create(['key' => 'pricing', 'slug' => 'pricing']);

        $this->actingAs($this->admin())->put(route('admin.pages.update', $page), [
            'name' => 'Pricing',
            'slug' => 'admin',
            'is_active' => true,
            'robots_index' => true,
            'robots_follow' => true,
            'og_type' => 'website',
            'twitter_card' => 'summary_large_image',
            'sitemap_priority' => '0.5',
            'sitemap_frequency' => 'weekly',
        ])->assertSessionHasErrors('slug');

        $this->assertSame('pricing', $page->fresh()->slug);
    }

    public function test_a_slug_must_be_unique(): void
    {
        Page::factory()->create(['key' => 'home', 'slug' => 'home']);
        $pricing = Page::factory()->create(['key' => 'pricing', 'slug' => 'pricing']);

        $this->actingAs($this->admin())->put(route('admin.pages.update', $pricing), [
            'name' => 'Pricing',
            'slug' => 'home',
            'is_active' => true,
            'robots_index' => true,
            'robots_follow' => true,
            'og_type' => 'website',
            'twitter_card' => 'summary_large_image',
            'sitemap_priority' => '0.5',
            'sitemap_frequency' => 'weekly',
        ])->assertSessionHasErrors('slug');
    }
}
