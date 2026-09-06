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
            'meta_description' => 'Two plans, one upgrade path. CoreX Team is R450 per agent, flat, up to 10 agents. CoreX Agency is R1 495 base + R295 per agent with seats that get cheaper as you grow.',
            'sitemap_priority' => '0.8',
            'sitemap_frequency' => 'monthly',
        ]);

        Page::updateOrCreate(['key' => 'mobile-app'], [
            'name' => 'Mobile App',
            'slug' => 'mobile-app',
            'meta_title' => 'CoreX OS — Mobile App',
            'meta_description' => 'A clickable, fully simulated replica of the CoreX OS mobile app, wrapped in a guided tour.',
            // A demo simulator, not indexable marketing content.
            'robots_index' => false,
            'sitemap_priority' => '0.1',
            'sitemap_frequency' => 'yearly',
        ]);

        Page::updateOrCreate(['key' => 'contact'], [
            'name' => 'Contact',
            'slug' => 'contact',
            'meta_title' => 'Contact — CoreX OS',
            'meta_description' => 'Get in touch with the CoreX OS team — questions, support, or anything else.',
            'sitemap_priority' => '0.5',
            'sitemap_frequency' => 'yearly',
        ]);
    }
}
