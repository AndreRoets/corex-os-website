<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The live site never runs PageSeeder, so a new page needs its row from a
     * migration. insertOrIgnore on the unique key, as with take-on: an existing
     * row an admin has edited is never touched.
     */
    public function up(): void
    {
        $now = now();

        DB::table('pages')->insertOrIgnore([
            'key' => 'rentals',
            'name' => 'Rental CRM',
            'slug' => 'rental-management-software',
            'is_active' => true,
            'meta_title' => 'Rental Management Software & CRM, South Africa | CoreX OS',
            'meta_description' => 'The rental CRM for South African letting agents: online tenant applications and vetting, leases and renewals, lease notices, inspections and maintenance.',
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
        // Not removed: once the row exists an admin may have edited it.
    }
};
