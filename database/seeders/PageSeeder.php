<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * One row per entry in App\Support\PageRoutes::actions(), so every
 * database-slug-driven route resolves from the moment migrations run rather
 * than falling back to its key. Idempotent — safe to re-run.
 */
class PageSeeder extends Seeder
{
    public function run(): void
    {
        // The home page always renders at the site root regardless of its
        // slug (see routes/web.php) — the slug here is kept for reference
        // and for the canonical URL it implies, not to build the path.
        Page::updateOrCreate(['key' => 'home'], [
            'name' => 'Home',
            'slug' => 'home',
            'meta_title' => 'CoreX OS — The Real Estate Operating System',
            'meta_description' => 'CoreX OS is the all-in-one operating system for a real estate agency — listings, deals, documents, e-signature, compliance and a domain AI in one source of truth. Book a demo.',
            'sitemap_priority' => '1.0',
            'sitemap_frequency' => 'weekly',
        ]);

        Page::updateOrCreate(['key' => 'pricing'], [
            'name' => 'Pricing',
            'slug' => 'pricing',
            'meta_title' => 'Pricing — CoreX OS',
            'meta_description' => 'One price per band, unlimited agents within it. From R450 a month for one agent to R5,095 for up to 30, set automatically by your active agents. No setup fees.',
            'sitemap_priority' => '0.8',
            'sitemap_frequency' => 'monthly',
        ]);

        Page::updateOrCreate(['key' => 'features'], [
            'name' => 'Features',
            'slug' => 'features',
            'meta_title' => 'Every feature, every plan — CoreX OS',
            'meta_description' => 'Everything CoreX OS does, in one place. Listings, deals, contacts, presentations, compliance, portals and more, all included on every plan.',
            'sitemap_priority' => '0.8',
            'sitemap_frequency' => 'monthly',
        ]);

        Page::updateOrCreate(['key' => 'mobile-app'], [
            'name' => 'Mobile App',
            'slug' => 'mobile-app',
            'meta_title' => 'Mobile app — CoreX OS',
            'meta_description' => 'Download the CoreX OS mobile app for Android or iPhone, and try it right now with a demo login — listings, deals, contacts and documents in your pocket.',
            // This used to be a noindex simulator. It is now the app's download
            // page, which is exactly the sort of page we want found.
            'robots_index' => true,
            'sitemap_priority' => '0.7',
            'sitemap_frequency' => 'monthly',
        ]);

        Page::updateOrCreate(['key' => 'contact'], [
            'name' => 'Contact',
            'slug' => 'contact',
            'meta_title' => 'Contact — CoreX OS',
            'meta_description' => 'Get in touch with the CoreX OS team — questions, support, or anything else.',
            'sitemap_priority' => '0.5',
            'sitemap_frequency' => 'yearly',
        ]);

        Page::updateOrCreate(['key' => 'take-on'], [
            'name' => 'Moving to CoreX',
            'slug' => 'take-on',
            'meta_title' => 'Moving to CoreX — how take-on works',
            'meta_description' => 'How agencies move to CoreX OS: no take-on fee, your onboarding month free, and we handle the migration from your current system.',
            'sitemap_priority' => '0.7',
            'sitemap_frequency' => 'monthly',
        ]);
    }
}
