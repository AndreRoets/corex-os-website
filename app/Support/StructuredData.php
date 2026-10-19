<?php

namespace App\Support;

/**
 * The schema.org @graph the layout prints in every public page's head.
 *
 * Built from the same sources the pages render — the price list, the take-on
 * questions, APP_URL — so the structured data cannot drift from what a visitor
 * reads. An admin's per-page JSON-LD is printed after this, not instead of it.
 */
class StructuredData
{
    /**
     * @param  string|null  $routeName  the current route's name, which identifies the page
     * @param  string  $canonical  the page's canonical URL
     * @param  string  $pageName  what the page is called, for its breadcrumb
     * @return array<string, mixed>
     */
    public static function graph(?string $routeName, string $canonical, string $pageName): array
    {
        $root = CanonicalUrl::root();
        $organizationId = $root.'/#organization';

        $graph = [
            [
                '@type' => 'Organization',
                '@id' => $organizationId,
                'name' => 'CoreX OS',
                'url' => $root,
                'logo' => CanonicalUrl::to('images/corex-mark.png'),
                'email' => config('corex.contact_email'),
                'areaServed' => 'South Africa',
            ],
            [
                '@type' => 'SoftwareApplication',
                '@id' => $root.'/#software',
                'name' => 'CoreX OS',
                'url' => $root,
                'applicationCategory' => 'BusinessApplication',
                'operatingSystem' => 'Web, Android, iOS',
                'description' => 'The all-in-one operating system for a real estate agency — listings, contacts, deals, documents, e-signature, compliance and a domain AI in a single source of truth.',
                'publisher' => ['@id' => $organizationId],
                'offers' => [
                    '@type' => 'AggregateOffer',
                    'priceCurrency' => Pricing::CURRENCY,
                    'lowPrice' => Pricing::lowestMonthly(),
                    'highPrice' => Pricing::highestMonthly(),
                    'offerCount' => count(Pricing::BANDS),
                    'url' => self::urlForRoute('pricing'),
                ],
            ],
        ];

        if ($routeName === 'home') {
            $graph[] = [
                '@type' => 'WebSite',
                '@id' => $root.'/#website',
                'name' => 'CoreX OS',
                'url' => $root,
                'inLanguage' => 'en-ZA',
                'publisher' => ['@id' => $organizationId],
            ];
        } else {
            $graph[] = [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $root],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => $pageName, 'item' => $canonical],
                ],
            ];
        }

        // Only pages that actually show these questions may claim them. The
        // pricing page has none to offer yet, so it gets no FAQPage.
        $faqs = match ($routeName) {
            'take-on' => TakeOnFaq::items(),
            default => [],
        };

        if ($faqs !== []) {
            $graph[] = [
                '@type' => 'FAQPage',
                'url' => $canonical,
                'mainEntity' => array_map(fn (array $faq) => [
                    '@type' => 'Question',
                    'name' => $faq[0],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => self::plainText($faq[1])],
                ], $faqs),
            ];
        }

        return ['@context' => 'https://schema.org', '@graph' => $graph];
    }

    /**
     * JSON safe to print inside a <script> element. JSON_HEX_TAG writes < and >
     * as < and >, so no value — a page name, an FAQ — can close the
     * element early with "</script>".
     *
     * @param  array<string, mixed>  $data
     */
    public static function encode(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_THROW_ON_ERROR);
    }

    private static function urlForRoute(string $name): string
    {
        $path = parse_url(route($name), PHP_URL_PATH) ?: '/';

        return CanonicalUrl::to($path);
    }

    private static function plainText(string $html): string
    {
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim((string) preg_replace('/\s+/u', ' ', $text));
    }
}
