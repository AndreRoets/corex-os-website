<?php

namespace App\Support;

use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;

/**
 * The map between a manageable page's stable `key` and the controller action
 * that renders it, and the list of slugs a page may never take.
 *
 * routes/web.php walks this list on boot and registers one route per entry,
 * at whatever slug the matching `pages` row currently holds — see
 * bootRoutesFromDatabase() there. Nothing here is a route itself.
 */
class PageRoutes
{
    /**
     * The URL path a page with this key/slug renders at.
     *
     * The home page always renders at the site root regardless of what its
     * own slug says — every other page renders at its slug. Shared by
     * routes/web.php (registering the live route) and App\Support\SitemapBuilder
     * (listing the same address), so the two can never disagree.
     */
    public static function pathFor(string $key, string $slug): string
    {
        return $key === 'home' ? '/' : '/'.ltrim($slug, '/');
    }

    /**
     * @return array<string, array{0: class-string, 1: string}>
     */
    public static function actions(): array
    {
        return [
            'home' => [PageController::class, 'home'],
            'pricing' => [PageController::class, 'pricing'],
            'mobile-app' => [PageController::class, 'mobileApp'],
            'contact' => [ContactController::class, 'show'],
        ];
    }

    /**
     * Every slug spoken for by a route that is not database-driven. An admin
     * can never point a page at one of these — see StorePageRequest /
     * UpdatePageRequest — because it would shadow a system route that Laravel
     * would otherwise have matched first.
     *
     * @return list<string>
     */
    public static function reservedSlugs(): array
    {
        return [
            'admin',
            'login',
            'logout',
            'register',
            'dashboard',
            'settings',
            'forgot-password',
            'reset-password',
            'verify-email',
            'confirm-password',
            'media',
            'sitemap.xml',
            'robots.txt',
            'up',
            'webinars',
            'demo',
            'enquire',
        ];
    }
}
