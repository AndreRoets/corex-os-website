<?php

namespace Tests\Unit;

use App\Support\Sast;
use PHPUnit\Framework\TestCase;

/**
 * South African time, and the one place the form's times are made unambiguous.
 */
class SastTest extends TestCase
{
    /**
     * The whole reason the time inputs became dropdowns: in an AM/PM control
     * "12:00 AM" is midnight, so picking it for a midday finish gets refused as
     * earlier than a 10:00 start — and the form looks like it is arguing.
     */
    public function test_noon_and_midnight_are_spelled_out(): void
    {
        $this->assertSame('12:00 midnight', Sast::timeLabel('00:00'));
        $this->assertSame('12:00 noon', Sast::timeLabel('12:00'));
    }

    public function test_other_times_read_the_way_people_say_them(): void
    {
        $this->assertSame('10:00 am', Sast::timeLabel('10:00'));
        $this->assertSame('1:30 pm', Sast::timeLabel('13:30'));
        $this->assertSame('11:45 pm', Sast::timeLabel('23:45'));
        $this->assertSame('12:30 am', Sast::timeLabel('00:30'));
        $this->assertSame('12:30 pm', Sast::timeLabel('12:30'));
    }

    public function test_the_dropdown_covers_the_day_in_quarter_hours(): void
    {
        $choices = Sast::timeChoices();

        $this->assertCount(96, $choices);
        $this->assertArrayHasKey('00:00', $choices);
        $this->assertArrayHasKey('23:45', $choices);
        $this->assertArrayNotHasKey('10:20', $choices);
    }

    /**
     * An existing webinar set off the quarter-hour grid must keep the time it
     * was actually given, rather than being nudged to the nearest quarter.
     */
    public function test_a_stored_time_off_the_grid_is_kept(): void
    {
        $choices = Sast::timeChoices('10:20');

        $this->assertArrayHasKey('10:20', $choices);
        $this->assertSame('10:20 am', $choices['10:20']);

        // And it lands in the right place in the list, not at the end.
        $keys = array_keys($choices);
        $this->assertLessThan(array_search('10:30', $keys, true), array_search('10:20', $keys, true));
    }

    public function test_a_date_and_a_time_become_one_sast_timestamp(): void
    {
        $this->assertSame(
            '2026-09-10T14:00:00+02:00',
            Sast::fromDateAndTime('2026-09-10', '14:00'),
        );

        $this->assertNull(Sast::fromDateAndTime('2026-09-10', null));
        $this->assertNull(Sast::fromDateAndTime(null, '14:00'));
    }

    public function test_the_gap_between_two_times_is_the_duration_corex_stores(): void
    {
        $this->assertSame(90, Sast::minutesBetween(
            Sast::fromDateAndTime('2026-09-10', '14:00'),
            Sast::fromDateAndTime('2026-09-10', '15:30'),
        ));

        // Backwards or equal is not a duration — the caller must refuse it
        // rather than send CoreX nothing and let it apply its own default.
        $this->assertNull(Sast::minutesBetween(
            Sast::fromDateAndTime('2026-09-10', '10:00'),
            Sast::fromDateAndTime('2026-09-10', '00:00'),
        ));
        $this->assertNull(Sast::minutesBetween(
            Sast::fromDateAndTime('2026-09-10', '10:00'),
            Sast::fromDateAndTime('2026-09-10', '10:00'),
        ));
    }
}
