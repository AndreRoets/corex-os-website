<?php

namespace App\Support;

/**
 * What the agency websites page shows: the agency sites already live, and the
 * example templates.
 *
 * The templates are fictional agencies with made-up listings, there to show
 * range. Each key has a matching view in resources/views/website-templates,
 * which renders both the full preview and, scaled down in a frame, its card.
 */
class WebsiteShowcase
{
    /**
     * @return list<array{name: string, url: string, domain: string, tagline: string, description: string}>
     */
    public static function liveSites(): array
    {
        return [
            [
                'name' => 'Home Finders Coastal',
                'url' => 'https://hfcoastal.co.za/',
                'domain' => 'hfcoastal.co.za',
                'tagline' => 'The Mandate Company',
                'description' => 'A live agency website, designed and built by the CoreX team.',
            ],
        ];
    }

    /**
     * Fonts are Google Fonts family specs, loaded on the preview page only.
     *
     * @return array<string, array{name: string, style: string, description: string, palette: list<string>, fonts: string}>
     */
    public static function templates(): array
    {
        return [
            'luxury' => [
                'name' => 'Aurum Estates',
                'style' => 'Luxury',
                'description' => 'Black and gold, a serif display face and full-bleed photography for a high-end portfolio.',
                'palette' => ['#0c0b09', '#c8a96a', '#f5efe4'],
                'fonts' => 'family=Cormorant+Garamond:wght@400;500;600&family=Jost:wght@300;400;500',
            ],
            'coastal' => [
                'name' => 'Saltwater Properties',
                'style' => 'Coastal',
                'description' => 'Light and airy, sea blues and soft curves for a holiday and seaside agency.',
                'palette' => ['#f4f9fb', '#1f7a99', '#f2d7b6'],
                'fonts' => 'family=DM+Serif+Display&family=DM+Sans:wght@400;500;700',
            ],
            'minimal' => [
                'name' => 'Grid & Co.',
                'style' => 'Modern minimal',
                'description' => 'Monochrome, oversized type and fine rules. The listings do all the talking.',
                'palette' => ['#ffffff', '#111111', '#e5e5e5'],
                'fonts' => 'family=Space+Grotesk:wght@300;400;500;700',
            ],
            'urban' => [
                'name' => 'BLOK Realty',
                'style' => 'Bold urban',
                'description' => 'Thick borders, a loud yellow and chunky type for a young, city-living brand.',
                'palette' => ['#fff8e1', '#ffd400', '#141414'],
                'fonts' => 'family=Archivo+Black&family=Archivo:wght@400;600',
            ],
            'classic' => [
                'name' => 'Hearth Homes',
                'style' => 'Warm classic',
                'description' => 'Cream and terracotta, friendly serifs and agent faces up front for a suburban family agency.',
                'palette' => ['#fbf6ee', '#b65a3a', '#2f3a35'],
                'fonts' => 'family=Fraunces:opsz,wght@9..144,400;9..144,600&family=Nunito+Sans:wght@400;600;700',
            ],
            'country' => [
                'name' => 'Veld & Vine',
                'style' => 'Country',
                'description' => 'Earthy greens and generous space for farms, smallholdings and lifestyle estates.',
                'palette' => ['#eef0e6', '#3f5a3c', '#c99a4b'],
                'fonts' => 'family=Lora:wght@400;500;600&family=Work+Sans:wght@400;500;600',
            ],
        ];
    }
}
