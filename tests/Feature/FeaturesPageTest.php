<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeaturesPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_features_page_renders_every_listed_area(): void
    {
        // The row comes from the add_features_page migration, which RefreshDatabase runs.
        $this->assertNotNull(Page::where('key', 'features')->first());

        $this->get(route('features'))
            ->assertOk()
            ->assertSee('Everything is included')
            ->assertSee('Deals &amp; Pipeline', false)
            ->assertSee('Settlement and payslip printing')
            ->assertDontSee('Merge duplicate');
    }

    public function test_the_features_page_is_linked_from_the_nav(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('href="'.route('features').'"', false);
    }

    public function test_the_features_page_is_registered_from_the_pages_table(): void
    {
        $this->assertSame('/features', route('features', [], false));
    }
}
