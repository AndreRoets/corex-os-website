<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();

            $table->string('site_name')->nullable();
            $table->string('default_meta_description')->nullable();
            $table->string('default_og_image')->nullable();
            $table->string('default_twitter_handle')->nullable();

            // Where a submitted contact form goes. Not part of the SEO/analytics
            // config above, but it lives here for the same reason they do: one
            // admin-editable row rather than an environment variable nobody
            // remembers to change.
            $table->string('contact_recipient_email')->nullable();

            $table->string('ga4_measurement_id')->nullable();
            $table->string('gtm_container_id')->nullable();
            $table->string('google_search_console_verification')->nullable();
            $table->string('google_ads_conversion_id')->nullable();
            $table->string('google_ads_conversion_label')->nullable();

            $table->text('head_scripts')->nullable();
            $table->text('body_scripts')->nullable();
            $table->text('robots_txt')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
