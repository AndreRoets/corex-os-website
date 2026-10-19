<?php

namespace Tests\Feature;

use App\Support\TakeOnFaq;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TakeOnPageTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();

        parent::tearDown();
    }

    public function test_the_take_on_page_renders_with_its_seo_metadata(): void
    {
        // From the `pages` row the migrations insert, not the template's props.
        $this->get(route('take-on'))
            ->assertOk()
            ->assertSee('<title>Switch Your Estate Agent CRM, No Take-On Fee | CoreX OS</title>', false)
            ->assertSee('Moving to CoreX OS costs nothing to start: no take-on fee, a free onboarding month, and we migrate your listings and contacts from your current CRM.')
            ->assertSee('Moving to CoreX costs you')
            ->assertSee(route('contact'))
            ->assertSee(route('pricing'));
    }

    public function test_the_take_on_row_is_editable_from_the_admin_panel(): void
    {
        $this->assertDatabaseHas('pages', ['key' => 'take-on', 'name' => 'Moving to CoreX', 'robots_index' => true]);
    }

    public function test_the_take_on_migration_never_overwrites_an_existing_row(): void
    {
        DB::table('pages')->where('key', 'take-on')->update(['meta_title' => 'An admin wrote this', 'sitemap_priority' => '0.9']);

        (require database_path('migrations/2026_10_19_100000_add_take_on_page.php'))->up();

        $this->assertSame(1, DB::table('pages')->where('key', 'take-on')->count());
        $this->assertDatabaseHas('pages', ['key' => 'take-on', 'meta_title' => 'An admin wrote this', 'sitemap_priority' => '0.9']);
    }

    public function test_the_page_questions_are_published_as_an_faq(): void
    {
        $html = $this->get(route('take-on'))->assertOk()->getContent();

        preg_match('#<script type="application/ld\+json">(\{"@context":"https://schema.org","@graph".*?)</script>#s', $html, $match);
        $faq = collect(json_decode($match[1], true, flags: JSON_THROW_ON_ERROR)['@graph'])->firstWhere('@type', 'FAQPage');

        $this->assertNotNull($faq);
        $this->assertCount(count(TakeOnFaq::items()), $faq['mainEntity']);

        // Every question in the markup is one a visitor can read on the page.
        foreach ($faq['mainEntity'] as $question) {
            $this->assertStringContainsString(e($question['name']), $html);
        }

        // Answers are plain text — the link in the billing answer is stripped.
        $billing = collect($faq['mainEntity'])->firstWhere('name', 'When does billing start?');
        $this->assertStringContainsString('See the price list for current rates.', $billing['acceptedAnswer']['text']);
        $this->assertStringNotContainsString('<a ', $billing['acceptedAnswer']['text']);
    }

    public function test_the_timeline_names_this_month_and_the_two_after_it(): void
    {
        CarbonImmutable::setTestNow('2026-09-19 10:00:00');

        $this->get(route('take-on'))
            ->assertSeeInOrder(['data-month="1">September', 'data-month="2">October', 'data-month="3">November'], false);
    }

    public function test_the_timeline_does_not_skip_a_short_month_or_stall_over_new_year(): void
    {
        // 31 January + 1 month would overflow into March without startOfMonth().
        CarbonImmutable::setTestNow('2027-01-31 12:00:00');
        $this->get(route('take-on'))
            ->assertSeeInOrder(['data-month="1">January', 'data-month="2">February', 'data-month="3">March'], false);

        CarbonImmutable::setTestNow('2026-12-15 12:00:00');
        $this->get(route('take-on'))
            ->assertSeeInOrder(['data-month="1">December', 'data-month="2">January', 'data-month="3">February'], false);
    }

    public function test_the_timeline_follows_south_african_time_not_utc(): void
    {
        // 23:30 UTC on 30 September is already 1 October in SAST.
        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-09-30 23:30:00', 'UTC'));

        $this->get(route('take-on'))
            ->assertSee('data-month="1">October', false);
    }

    public function test_the_take_on_page_is_linked_from_the_site_navigation(): void
    {
        $this->get('/')->assertSee(route('take-on'));
    }
}
