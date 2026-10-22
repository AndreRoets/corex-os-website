<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The company's hard facts — registered name, CIPC number, address, phone,
     * year founded. They tell search engines and AI assistants which "CoreX"
     * this is, and must match the Google Business Profile word for word, so
     * they are admin-editable rather than hardcoded.
     */
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('company_legal_name')->nullable()->after('same_as');
            $table->string('company_registration_number')->nullable()->after('company_legal_name');
            $table->string('company_street_address')->nullable()->after('company_registration_number');
            $table->string('company_locality')->nullable()->after('company_street_address');
            $table->string('company_region')->nullable()->after('company_locality');
            $table->string('company_postal_code')->nullable()->after('company_region');
            $table->string('company_telephone')->nullable()->after('company_postal_code');
            $table->unsignedSmallInteger('company_founding_year')->nullable()->after('company_telephone');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'company_legal_name',
                'company_registration_number',
                'company_street_address',
                'company_locality',
                'company_region',
                'company_postal_code',
                'company_telephone',
                'company_founding_year',
            ]);
        });
    }
};
