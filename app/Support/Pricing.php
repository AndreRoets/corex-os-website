<?php

namespace App\Support;

/**
 * The published price list. The pricing page, its calculator and the
 * structured data search engines read all come from here, so none of them can
 * quote a different price.
 */
class Pricing
{
    /**
     * Each band is [from agents, to agents, monthly price in rand].
     *
     * @var list<array{0: int, 1: int, 2: int}>
     */
    public const BANDS = [
        [1, 1, 450],
        [2, 2, 900],
        [3, 5, 1495],
        [6, 10, 2495],
        [11, 15, 3295],
        [16, 20, 3995],
        [21, 25, 4595],
        [26, 30, 5095],
    ];

    /** Monthly rand per branch beyond the first. */
    public const BRANCH_RATE = 750;

    /** Headcounts from here up are quoted, not banded. */
    public const QUOTE_FROM = 31;

    public const CURRENCY = 'ZAR';

    public static function lowestMonthly(): int
    {
        return min(array_column(self::BANDS, 2));
    }

    public static function highestMonthly(): int
    {
        return max(array_column(self::BANDS, 2));
    }
}
