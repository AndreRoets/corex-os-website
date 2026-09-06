<?php

namespace Tests\Unit;

use App\Support\PageRoutes;
use Tests\TestCase;

/**
 * routes/web.php and App\Support\SitemapBuilder both build a page's live URL
 * from this one function, so the admin's sitemap listing can never disagree
 * with what a visitor actually lands on.
 */
class PageRoutesTest extends TestCase
{
    public function test_the_home_page_always_resolves_to_the_root_regardless_of_its_slug(): void
    {
        $this->assertSame('/', PageRoutes::pathFor('home', 'home'));
        $this->assertSame('/', PageRoutes::pathFor('home', 'anything-else'));
    }

    public function test_every_other_page_resolves_to_its_slug(): void
    {
        $this->assertSame('/pricing', PageRoutes::pathFor('pricing', 'pricing'));
        $this->assertSame('/plans', PageRoutes::pathFor('pricing', 'plans'));
    }

    public function test_a_leading_slash_on_the_stored_slug_is_not_doubled(): void
    {
        $this->assertSame('/pricing', PageRoutes::pathFor('pricing', '/pricing'));
    }

    public function test_reserved_slugs_cover_every_hardcoded_route(): void
    {
        $reserved = PageRoutes::reservedSlugs();

        foreach (['admin', 'login', 'logout', 'media', 'sitemap.xml', 'robots.txt', 'up', 'webinars'] as $slug) {
            $this->assertContains($slug, $reserved);
        }
    }
}
