<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bing's site verification sits beside Google's, and `same_as` holds the
     * company's profiles elsewhere (LinkedIn, Capterra, …), one URL per line.
     * Both are admin-editable so a new listing goes live without a deploy.
     */
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('bing_site_verification')->nullable()->after('google_search_console_verification');
            $table->text('same_as')->nullable()->after('robots_txt');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['bing_site_verification', 'same_as']);
        });
    }
};
