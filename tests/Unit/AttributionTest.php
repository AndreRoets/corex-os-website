<?php

namespace Tests\Unit;

use App\Support\Attribution;
use PHPUnit\Framework\TestCase;

class AttributionTest extends TestCase
{
    /**
     * @return iterable<string, array{0: array<string, string|null>, 1: string}>
     */
    public static function channels(): iterable
    {
        yield 'nothing at all' => [[], 'Direct'];
        yield 'google referrer' => [['referrer' => 'https://www.google.com/'], 'Organic search'];
        yield 'google country domain' => [['referrer' => 'https://www.google.co.za/search?q=corex'], 'Organic search'];
        yield 'bing referrer' => [['referrer' => 'https://www.bing.com/search?q=x'], 'Organic search'];
        yield 'gclid beats referrer' => [['referrer' => 'https://www.google.com/', 'gclid' => 'abc'], 'Paid search'];
        yield 'cpc medium' => [['utm_source' => 'google', 'utm_medium' => 'cpc'], 'Paid search'];
        yield 'paid social medium' => [['utm_source' => 'facebook', 'utm_medium' => 'paid_social'], 'Paid social'];
        yield 'fbclid' => [['fbclid' => 'xyz'], 'Social'];
        yield 'linkedin referrer' => [['referrer' => 'https://www.linkedin.com/feed/'], 'Social'];
        yield 'email medium' => [['utm_source' => 'mailchimp', 'utm_medium' => 'email'], 'Email'];
        yield 'campaign without a known medium' => [['utm_source' => 'partner', 'utm_medium' => 'banner'], 'Campaign'];
        yield 'other site' => [['referrer' => 'https://property24.com/some-page'], 'Referral'];
        yield 'medium is case-insensitive' => [['utm_medium' => 'CPC'], 'Paid search'];
    }

    /**
     * @dataProvider channels
     *
     * @param  array<string, string|null>  $attribution
     */
    public function test_channels_are_classified(array $attribution, string $expected): void
    {
        $this->assertSame($expected, Attribution::channel($attribution));
    }
}
