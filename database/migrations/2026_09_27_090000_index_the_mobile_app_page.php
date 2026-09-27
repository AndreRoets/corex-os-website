<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * /mobile-app used to be a noindex interactive simulator. It is now the
     * app's download page — store links and a demo login — which is exactly
     * the kind of page we want found and in the sitemap.
     *
     * PageSeeder says the same thing for fresh installs; this is for the ones
     * already running, where the row is stored and a re-seed is not part of a
     * deploy. SitemapBuilder filters on robots_index, so without this the page
     * would keep telling Google to stay away.
     */
    public function up(): void
    {
        $page = DB::table('pages')->where('key', 'mobile-app')->first();

        if ($page === null) {
            return;
        }

        $changes = [
            'robots_index' => true,
            'sitemap_priority' => '0.7',
            'sitemap_frequency' => 'monthly',
        ];

        // The metadata is editable from the admin panel, so only replace it
        // where it still describes the simulator nobody can reach any more.
        if (str_contains((string) $page->meta_description, 'simulated replica')) {
            $changes['meta_title'] = 'Mobile app — CoreX OS';
            $changes['meta_description'] = 'Download the CoreX OS mobile app for Android or iPhone, and try it right now with a demo login — listings, deals, contacts and documents in your pocket.';
        }

        DB::table('pages')->where('key', 'mobile-app')->update($changes);
    }

    public function down(): void
    {
        DB::table('pages')->where('key', 'mobile-app')->update([
            'robots_index' => false,
            'sitemap_priority' => '0.1',
            'sitemap_frequency' => 'yearly',
        ]);
    }
};
