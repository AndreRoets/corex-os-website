<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Throwable;

/**
 * South African time, in one place.
 *
 * Every webinar time the visitor sees and every time we send to CoreX is SAST.
 * Two rules follow, and both are easy to get wrong by hand:
 *
 *  1. Reading — CoreX sends ISO 8601 *with* the offset. Format it, never shift
 *     it. Displaying a webinar an hour out is the kind of bug nobody notices
 *     until somebody misses the call.
 *  2. Writing — a <input type="datetime-local"> hands us a naive string with no
 *     offset at all. Interpreting that as UTC would move every webinar two
 *     hours. It is SAST, and it must go to CoreX stamped as such.
 *
 * SAST has no daylight saving, so the offset is always +02:00 — but we name the
 * zone and let Carbon render it, rather than gluing "+02:00" onto a string.
 */
final class Sast
{
    public const ZONE = 'Africa/Johannesburg';

    /**
     * Parse an ISO 8601 timestamp from CoreX and move it into SAST for display.
     *
     * Returns null rather than throwing: a malformed date on one row of a list
     * should render as "—", not take the whole admin screen down.
     */
    public static function parse(?string $iso): ?CarbonImmutable
    {
        if (blank($iso)) {
            return null;
        }

        try {
            return CarbonImmutable::parse($iso)->setTimezone(self::ZONE);
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * "Thursday, 10 September 2026 at 14:00 SAST" — the public page.
     */
    public static function long(?string $iso): ?string
    {
        return self::parse($iso)?->format('l, j F Y \a\t H:i').' SAST';
    }

    /**
     * "10 Sep 2026, 14:00" — admin tables, where space is tight.
     */
    public static function short(?string $iso): ?string
    {
        return self::parse($iso)?->format('j M Y, H:i');
    }

    /**
     * The value for a <input type="datetime-local">, which has no offset field.
     */
    public static function forInput(?string $iso): ?string
    {
        return self::parse($iso)?->format('Y-m-d\TH:i');
    }

    /**
     * How many whole minutes from one moment to another, or null if either is
     * unreadable or the end is not after the start.
     *
     * CoreX stores a start plus a duration; people think in "two till three".
     * This is the one conversion between those, so the form can ask the question
     * the way it is actually asked out loud.
     */
    public static function minutesBetween(?string $startIso, ?string $endIso): ?int
    {
        $start = self::parse($startIso);
        $end = self::parse($endIso);

        if (! $start || ! $end || $end->lessThanOrEqualTo($start)) {
            return null;
        }

        return (int) $start->diffInMinutes($end);
    }

    /**
     * The date half, for an <input type="date">.
     */
    public static function dateForInput(?string $iso): ?string
    {
        return self::parse($iso)?->format('Y-m-d');
    }

    /**
     * The time half, for an <input type="time">.
     */
    public static function timeForInput(?string $iso): ?string
    {
        return self::parse($iso)?->format('H:i');
    }

    /**
     * A start plus a duration, rendered back as the finishing time.
     */
    public static function endTimeForInput(?string $startIso, int|string|null $durationMinutes): ?string
    {
        $start = self::parse($startIso);

        if (! $start || ! is_numeric($durationMinutes)) {
            return null;
        }

        return $start->addMinutes((int) $durationMinutes)->format('H:i');
    }

    /**
     * Join a date and a time from the form into one SAST timestamp for CoreX.
     *
     * Split across two inputs because a webinar happens on ONE day, and asking
     * for the date twice is how you end up with a webinar that ends before it
     * starts — or a month earlier.
     */
    public static function fromDateAndTime(?string $date, ?string $time): ?string
    {
        if (blank($date) || blank($time)) {
            return null;
        }

        return self::fromInput(trim($date).'T'.trim($time));
    }

    /**
     * The inverse: a naive "2026-09-10T14:00" from the browser becomes
     * "2026-09-10T14:00:00+02:00" for CoreX. Explicitly SAST, never UTC and
     * never a bare local string.
     */
    public static function fromInput(?string $naive): ?string
    {
        if (blank($naive)) {
            return null;
        }

        try {
            return CarbonImmutable::parse($naive, self::ZONE)->toIso8601String();
        } catch (Throwable) {
            // Let CoreX's own validation produce the message — it is written for
            // a non-technical reader and we render it against the field.
            return $naive;
        }
    }
}
