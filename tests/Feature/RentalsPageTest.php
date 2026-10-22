<?php

namespace Tests\Feature;

use App\Support\Features\FeatureInventory;
use App\Support\RentalsFaq;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RentalsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_rentals_page_renders_at_its_search_friendly_address(): void
    {
        // Routes register before the test database exists, so here the page
        // answers at its key; live, routes/web.php reads this slug.
        $this->assertDatabaseHas('pages', ['key' => 'rentals', 'slug' => 'rental-management-software', 'robots_index' => true]);

        $this->get(route('rentals'))
            ->assertOk()
            ->assertSee('<title>Rental Management Software &amp; CRM, South Africa | CoreX OS</title>', false)
            ->assertSee('Rental management software for')
            ->assertSee('South African letting agents.')
            ->assertSee(route('pricing'));
    }

    public function test_every_rentals_feature_in_the_inventory_is_listed_and_nothing_else(): void
    {
        $html = $this->get(route('rentals'))->assertOk()->getContent();

        foreach (FeatureInventory::categories()['Rentals'] as [$name]) {
            $this->assertStringContainsString(e($name), $html);
        }

        // A neighbouring category's feature must not leak in.
        $this->assertStringNotContainsString('Pricing simulator', $html);
    }

    public function test_the_page_says_plainly_that_corex_does_not_handle_money(): void
    {
        $this->get(route('rentals'))
            ->assertSee('Does CoreX collect rent or run a trust account?')
            ->assertSee('not an accounting package', false)
            ->assertSee('Collecting rent');
    }

    public function test_the_page_questions_are_published_as_an_faq(): void
    {
        $html = $this->get(route('rentals'))->assertOk()->getContent();

        preg_match('#<script type="application/ld\+json">(\{"@context":"https://schema.org","@graph".*?)</script>#s', $html, $match);
        $faq = collect(json_decode($match[1], true, flags: JSON_THROW_ON_ERROR)['@graph'])->firstWhere('@type', 'FAQPage');

        $this->assertNotNull($faq);
        $this->assertCount(count(RentalsFaq::items()), $faq['mainEntity']);

        foreach ($faq['mainEntity'] as $question) {
            $this->assertStringContainsString(e($question['name']), $html);
            $this->assertStringNotContainsString('<a ', $question['acceptedAnswer']['text']);
        }
    }

    public function test_the_page_is_in_the_sitemap_and_linked_from_every_page(): void
    {
        $this->get('/sitemap.xml')->assertSee('/rental-management-software</loc>', false);
        $this->get('/')->assertSee(route('rentals'));
    }

    public function test_the_migration_never_overwrites_an_existing_row(): void
    {
        DB::table('pages')->where('key', 'rentals')->update(['meta_title' => 'An admin wrote this']);

        (require database_path('migrations/2026_10_22_100000_add_rentals_page.php'))->up();

        $this->assertSame(1, DB::table('pages')->where('key', 'rentals')->count());
        $this->assertDatabaseHas('pages', ['key' => 'rentals', 'meta_title' => 'An admin wrote this']);
    }
}
