<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\SitemapBuilder;
use Inertia\Inertia;
use Inertia\Response;

class SitemapController extends Controller
{
    public function index(SitemapBuilder $builder): Response
    {
        $settings = SiteSetting::current();
        $limit = $builder->previewLimit();

        $sections = $builder->sections()->map(fn (array $section) => [
            ...$section,
            'url_count' => count($section['urls']),
            'urls' => array_slice($section['urls'], 0, $limit),
        ]);

        return Inertia::render('Admin/Sitemap/Index', [
            'sections' => $sections->values(),
            'previewLimit' => $limit,
            'robotsReferencesSitemap' => str_contains($settings->defaultRobotsTxt(), '/sitemap.xml'),
            'searchConsoleVerified' => filled($settings->google_search_console_verification),
        ]);
    }
}
