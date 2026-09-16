<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Where a visitor came from, remembered for the length of their session.
 *
 * The contact form is rarely the first page anyone lands on. By the time it
 * is submitted the search result, ad or referring site that brought the
 * person here is several clicks back and the browser no longer says. So the
 * first request of every session records it (App\Http\Middleware\CaptureAttribution),
 * and a contact request copies it out of the session when it is saved.
 *
 * A later request that carries campaign parameters (utm_*, gclid, …)
 * replaces a plain first touch: a paid click is the more useful thing to
 * credit than the organic visit a week earlier that the same session
 * cookie happened to survive.
 */
class Attribution
{
    public const SESSION_KEY = 'attribution';

    /** Query parameters copied verbatim from the URL. */
    public const PARAMS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'fbclid'];

    /** Everything a stored attribution carries — the contact_requests columns of the same name. */
    public const COLUMNS = ['landing_page', 'referrer', ...self::PARAMS, 'first_seen_at'];

    private const SEARCH_ENGINES = ['google.', 'bing.com', 'yahoo.', 'duckduckgo.com', 'ecosia.org', 'yandex.', 'baidu.com', 'ask.com', 'startpage.com', 'search.brave.com'];

    private const SOCIAL = ['facebook.com', 'fb.com', 'instagram.com', 'linkedin.com', 'lnkd.in', 'twitter.com', 'x.com', 't.co', 'tiktok.com', 'youtube.com', 'youtu.be', 'pinterest.', 'reddit.com', 'whatsapp.com', 'threads.net'];

    private const PAID_MEDIUMS = ['cpc', 'ppc', 'paid', 'paidsearch', 'paid_search', 'paid-search', 'sem', 'display', 'cpm', 'paid_social', 'paidsocial', 'paid-social'];

    private const PAID_SOCIAL_MEDIUMS = ['paid_social', 'paidsocial', 'paid-social'];

    private const SOCIAL_MEDIUMS = ['social', 'social-media', 'social_media', 'sm'];

    public static function capture(Request $request): void
    {
        if (! $request->hasSession()) {
            return;
        }

        $session = $request->session();
        $incoming = self::campaignParams($request);
        $existing = $session->get(self::SESSION_KEY);

        // Keep the first touch unless this request brings a campaign and the
        // one we have does not.
        if (is_array($existing) && ($incoming === [] || self::hasCampaign($existing))) {
            return;
        }

        $session->put(self::SESSION_KEY, [
            'landing_page' => self::limitUrl($request->fullUrl()),
            'referrer' => self::externalReferrer($request),
            ...array_fill_keys(self::PARAMS, null),
            ...$incoming,
            'first_seen_at' => now()->toIso8601String(),
        ]);
    }

    /**
     * @return array<string, string|null>
     */
    public static function current(Request $request): array
    {
        $stored = $request->hasSession() ? $request->session()->get(self::SESSION_KEY) : null;

        return array_merge(array_fill_keys(self::COLUMNS, null), is_array($stored) ? $stored : []);
    }

    /**
     * The marketing channel a set of attribution columns points at.
     *
     * Deliberately coarse — a handful of buckets a person will actually use
     * to compare months, not GA's forty. Order matters: an explicit campaign
     * beats anything inferred from the referrer.
     *
     * @param  array<string, mixed>  $a
     */
    public static function channel(array $a): string
    {
        $medium = Str::lower((string) ($a['utm_medium'] ?? ''));
        $source = Str::lower((string) ($a['utm_source'] ?? ''));
        $host = self::host((string) ($a['referrer'] ?? ''));

        if (! empty($a['gclid']) || in_array($medium, self::PAID_MEDIUMS, true)) {
            return in_array($medium, self::PAID_SOCIAL_MEDIUMS, true) || ! empty($a['fbclid'])
                ? 'Paid social'
                : 'Paid search';
        }

        if (! empty($a['fbclid']) || in_array($medium, self::SOCIAL_MEDIUMS, true) || self::hostIn($host, self::SOCIAL)) {
            return 'Social';
        }

        if ($medium === 'email' || $source === 'newsletter') {
            return 'Email';
        }

        if ($source !== '' || $medium !== '' || ! empty($a['utm_campaign'])) {
            return 'Campaign';
        }

        if (self::hostIn($host, self::SEARCH_ENGINES)) {
            return 'Organic search';
        }

        if ($host !== '') {
            return 'Referral';
        }

        return 'Direct';
    }

    /**
     * @return array<string, string>
     */
    private static function campaignParams(Request $request): array
    {
        $params = [];

        foreach (self::PARAMS as $name) {
            $value = $request->query($name);

            if (is_string($value) && trim($value) !== '') {
                $params[$name] = Str::limit(trim($value), 150, '');
            }
        }

        return $params;
    }

    /**
     * @param  array<string, mixed>  $stored
     */
    private static function hasCampaign(array $stored): bool
    {
        foreach (self::PARAMS as $name) {
            if (! empty($stored[$name])) {
                return true;
            }
        }

        return false;
    }

    /**
     * The referrer, unless it is one of our own pages — in which case it
     * tells us nothing about where the person came from.
     */
    private static function externalReferrer(Request $request): ?string
    {
        $referrer = (string) $request->headers->get('referer', '');

        if ($referrer === '') {
            return null;
        }

        $host = self::host($referrer);

        if ($host === '' || $host === Str::lower(Str::after($request->getHost(), 'www.'))) {
            return null;
        }

        return self::limitUrl($referrer);
    }

    private static function host(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);

        return is_string($host) ? Str::lower(Str::after($host, 'www.')) : '';
    }

    /**
     * @param  list<string>  $needles
     */
    private static function hostIn(string $host, array $needles): bool
    {
        if ($host === '') {
            return false;
        }

        foreach ($needles as $needle) {
            if (str_contains($host, $needle)) {
                return true;
            }
        }

        return false;
    }

    private static function limitUrl(string $url): string
    {
        return Str::limit($url, 2000, '');
    }
}
