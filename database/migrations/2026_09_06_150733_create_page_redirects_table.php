<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_redirects', function (Blueprint $table) {
            $table->id();

            // No unique index on old_slug alone: a page can be renamed more
            // than once, and every former slug it ever held should keep
            // resolving to wherever the page lives now.
            $table->string('old_slug');
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('status_code')->default(301);

            $table->timestamps();

            $table->unique(['old_slug', 'page_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_redirects');
    }
};
