<?php

namespace App\Support;

use App\Models\Page;
use Illuminate\Support\Collection;

/**
 * Builds the sitemap as named sections, each a list of URLs. `admin.sitemap`
 * shows the sections to a human; sitemap.xml flattens them into XML. Adding a
 * new kind of URL to the sitemap means adding a section here — nowhere else
 * needs to change.
 */
class SitemapBuilder
{
    /** Sections show at most this many URLs each on the admin overview. */
    private const PREVIEW_LIMIT = 50;

    /**
     * @return Collection<int, array{key: string, label: string, description: string, urls: list<array{loc: string, priority: string, frequency: string}>}>
     */
    public function sections(): Collection
    {
        return collect([
            [
                'key' => 'pages',
                'label' => 'Pages',
                'description' => 'Static pages managed from the admin panel, excluding any marked inactive or set to noindex.',
                'urls' => $this->pageUrls(),
            ],
        ]);
    }

    /**
     * @return list<array{loc: string, priority: string, frequency: string}>
     */
    public function urls(): array
    {
        return $this->sections()->flatMap(fn (array $section) => $section['urls'])->all();
    }

    /**
     * @return list<array{loc: string, priority: string, frequency: string}>
     */
    private function pageUrls(): array
    {
        return Page::query()
            ->where('is_active', true)
            ->where('robots_index', true)
            ->orderBy('slug')
            ->get()
            ->map(fn (Page $page) => [
                'loc' => url(PageRoutes::pathFor($page->key, $page->slug)),
                'priority' => $page->sitemap_priority,
                'frequency' => $page->sitemap_frequency,
            ])
            ->all();
    }

    public function previewLimit(): int
    {
        return self::PREVIEW_LIMIT;
    }
}
