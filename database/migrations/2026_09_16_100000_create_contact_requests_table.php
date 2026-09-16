<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Every message sent through the contact page, with where the person
        // came from. The email to the sales inbox is the notification; this
        // row is the record — it is what lets a marketing person answer "which
        // channel produced this enquiry?" months later, when the email is long
        // buried. See App\Support\Attribution for how the source columns are
        // captured.
        Schema::create('contact_requests', function (Blueprint $table) {
            $table->id();

            $table->string('name', 120);
            $table->string('email', 180);
            $table->string('phone', 40)->nullable();
            $table->string('agency', 160)->nullable();
            $table->string('topic', 40)->nullable();
            $table->text('message');

            // The page the form was on (the contact page, at whatever slug it
            // had), and the page the session started on — the one a search
            // engine or ad actually sent them to.
            $table->text('page_url')->nullable();
            $table->text('landing_page')->nullable();
            $table->text('referrer')->nullable();

            $table->string('utm_source', 160)->nullable();
            $table->string('utm_medium', 160)->nullable();
            $table->string('utm_campaign', 160)->nullable();
            $table->string('utm_term', 160)->nullable();
            $table->string('utm_content', 160)->nullable();
            $table->string('gclid')->nullable();
            $table->string('fbclid')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 512)->nullable();

            // When the session that ended in this message first arrived.
            $table->timestamp('first_seen_at')->nullable();

            // Null means the notification email failed; the row is still here.
            $table->timestamp('emailed_at')->nullable();

            $table->timestamps();

            $table->index('created_at');
            $table->index('utm_source');
        });

        // Contact messages now go to the same inbox as demo requests
        // (MAIL_DEMO_TO), so a separate recipient setting would be a lie.
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('contact_recipient_email');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('contact_recipient_email')->nullable();
        });

        Schema::dropIfExists('contact_requests');
    }
};
