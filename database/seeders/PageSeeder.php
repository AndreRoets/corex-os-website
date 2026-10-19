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
            'meta_title' => 'Real Estate Software South Africa | CoreX OS',
            'meta_description' => 'CoreX OS is the estate agent CRM and real estate software for South African agencies: listings, deals, rentals, e-sign, FICA compliance and portal feeds.',
            'sitemap_priority' => '1.0',
            'sitemap_frequency' => 'weekly',
        ]);

        Page::updateOrCreate(['key' => 'pricing'], [
            'name' => 'Pricing',
            'slug' => 'pricing',
            'meta_title' => 'Estate Agent CRM Pricing, South Africa | CoreX OS',
            'meta_description' => 'Real estate software pricing from R450 a month for one agent to R5,095 for up to 30. Website, Property24 feeds, e-sign and data take-on included.',
            'sitemap_priority' => '0.8',
            'sitemap_frequency' => 'monthly',
        ]);

        Page::updateOrCreate(['key' => 'features'], [
            'name' => 'Features',
            'slug' => 'features',
            'meta_title' => 'Estate Agent CRM & Property Management Software | CoreX OS',
            'meta_description' => 'Every CoreX OS feature on every plan: estate agent CRM, listings, deals, rentals and property management, e-signature, FICA compliance and portal feeds.',
            'sitemap_priority' => '0.8',
            'sitemap_frequency' => 'monthly',
        ]);

        Page::updateOrCreate(['key' => 'mobile-app'], [
            'name' => 'Mobile App',
            'slug' => 'mobile-app',
            'meta_title' => 'Estate Agent CRM App for Android & iPhone | CoreX OS',
            'meta_description' => 'The CoreX OS app puts your estate agent CRM in your pocket: listings, deals, contacts and documents. Download for Android or iPhone, or try the demo.',
            // This used to be a noindex simulator. It is now the app's download
            // page, which is exactly the sort of page we want found.
            'robots_index' => true,
            'sitemap_priority' => '0.7',
            'sitemap_frequency' => 'monthly',
        ]);

        Page::updateOrCreate(['key' => 'contact'], [
            'name' => 'Contact',
            'slug' => 'contact',
            'meta_title' => 'Contact CoreX OS | Real Estate Software South Africa',
            'meta_description' => 'Talk to the CoreX OS team about real estate software for your South African agency: a demo, pricing, support or moving from your current CRM.',
            'sitemap_priority' => '0.5',
            'sitemap_frequency' => 'yearly',
        ]);

        Page::updateOrCreate(['key' => 'take-on'], [
            'name' => 'Moving to CoreX',
            'slug' => 'take-on',
            'meta_title' => 'Switch Your Estate Agent CRM, No Take-On Fee | CoreX OS',
            'meta_description' => 'Moving to CoreX OS costs nothing to start: no take-on fee, a free onboarding month, and we migrate your listings and contacts from your current CRM.',
            'sitemap_priority' => '0.7',
            'sitemap_frequency' => 'monthly',
        ]);
    }
}
