<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The live site never runs PageSeeder, so a new page needs its row from a
     * migration. insertOrIgnore on the unique key, as with rentals: an existing
     * row an admin has edited is never touched.
     */
    public function up(): void
    {
        $now = now();

        DB::table('pages')->insertOrIgnore([
            'key' => 'websites',
            'name' => 'Agency websites',
            'slug' => 'real-estate-website-templates',
            'is_active' => true,
            'meta_title' => 'Real Estate Website Design, Free With CoreX | CoreX OS',
            'meta_description' => 'Every CoreX plan includes an agency website, designed for you. See live agency sites and six example real estate website templates, or ask for something entirely your own.',
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
        // Not removed: once the row exists an admin may have edited it.
    }
};
