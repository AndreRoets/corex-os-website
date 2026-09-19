<?php

namespace Tests\Feature;

use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $this->get(route('take-on'))
            ->assertOk()
            ->assertSee('<title>Moving to CoreX — how take-on works</title>', false)
            ->assertSee('How agencies move to CoreX OS: no take-on fee, your onboarding month free, and we handle the migration from your current system.')
            ->assertSee('Moving to CoreX costs you')
            ->assertSee(route('contact'))
            ->assertSee(route('pricing'));
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
