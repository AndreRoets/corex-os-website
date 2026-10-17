<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The /features page is a new static page. PageSeeder creates its row on
     * fresh installs; this is for the ones already running, where a re-seed is
     * not part of a deploy. Insert only when the row is missing, so an admin's
     * edits to the metadata are never overwritten.
     */
    public function up(): void
    {
        if (DB::table('pages')->where('key', 'features')->exists()) {
            return;
        }

        $now = now();

        DB::table('pages')->insert([
            'key' => 'features',
            'name' => 'Features',
            'slug' => 'features',
            'is_active' => true,
            'meta_title' => 'Every feature, every plan — CoreX OS',
            'meta_description' => 'Everything CoreX OS does, in one place. Listings, deals, contacts, presentations, compliance, portals and more, all included on every plan.',
            'robots_index' => true,
            'robots_follow' => true,
            'og_type' => 'website',
            'twitter_card' => 'summary_large_image',
            'sitemap_priority' => '0.8',
            'sitemap_frequency' => 'monthly',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        DB::table('pages')->where('key', 'features')->delete();
    }
};
