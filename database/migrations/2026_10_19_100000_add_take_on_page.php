<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * /take-on is a static page whose row PageSeeder creates on fresh installs,
     * but the seeder was never run on the live site, so the page rendered from
     * its template alone: missing from the sitemap and from the admin panel.
     *
     * This inserts that one row and nothing else. Re-running the seeder instead
     * would updateOrCreate every page and overwrite SEO an admin has edited.
     * insertOrIgnore on the unique key means an existing row is never touched.
     */
    public function up(): void
    {
        $now = now();

        DB::table('pages')->insertOrIgnore([
            'key' => 'take-on',
            'name' => 'Moving to CoreX',
            'slug' => 'take-on',
            'is_active' => true,
            'meta_title' => 'Moving to CoreX — how take-on works',
            'meta_description' => 'How agencies move to CoreX OS: no take-on fee, your onboarding month free, and we handle the migration from your current system.',
            'robots_index' => true,
            'robots_follow' => true,
            'og_type' => 'website',
            'twitter_card' => 'summary_large_image',
            'sitemap_priority' => '0.7',
            'sitemap_frequency' => 'monthly',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        // Not removed: once the row exists an admin may have edited it, and the
        // page keeps rendering without it anyway.
    }
};
