<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Titles and descriptions written for the searches agencies actually make —
     * "real estate software South Africa", "estate agent CRM", "property
     * management software" — instead of the launch defaults.
     *
     * The metadata is editable from the admin panel, so a field is only
     * replaced where it still holds the seeded default it is listed against
     * here. Anything an admin has changed is left alone, field by field.
     *
     * key => [field => [seeded default, new value]]
     */
    private const CHANGES = [
        'home' => [
            'meta_title' => [
                'CoreX OS — The Real Estate Operating System',
                'Real Estate Software South Africa | CoreX OS',
            ],
            'meta_description' => [
                'CoreX OS is the all-in-one operating system for a real estate agency — listings, deals, documents, e-signature, compliance and a domain AI in one source of truth. Book a demo.',
                'CoreX OS is the estate agent CRM and real estate software for South African agencies: listings, deals, rentals, e-sign, FICA compliance and portal feeds.',
            ],
        ],
        'pricing' => [
            'meta_title' => [
                'Pricing — CoreX OS',
                'Estate Agent CRM Pricing, South Africa | CoreX OS',
            ],
            'meta_description' => [
                'One price per band, unlimited agents within it. From R450 a month for one agent to R5,095 for up to 30, set automatically by your active agents. No setup fees.',
                'Real estate software pricing from R450 a month for one agent to R5,095 for up to 30. Website, Property24 feeds, e-sign and data take-on included.',
            ],
        ],
        'features' => [
            'meta_title' => [
                'Every feature, every plan — CoreX OS',
                'Estate Agent CRM & Property Management Software | CoreX OS',
            ],
            'meta_description' => [
                'Everything CoreX OS does, in one place. Listings, deals, contacts, presentations, compliance, portals and more, all included on every plan.',
                'Every CoreX OS feature on every plan: estate agent CRM, listings, deals, rentals and property management, e-signature, FICA compliance and portal feeds.',
            ],
        ],
        'mobile-app' => [
            'meta_title' => [
                'Mobile app — CoreX OS',
                'Estate Agent CRM App for Android & iPhone | CoreX OS',
            ],
            'meta_description' => [
                'Download the CoreX OS mobile app for Android or iPhone, and try it right now with a demo login — listings, deals, contacts and documents in your pocket.',
                'The CoreX OS app puts your estate agent CRM in your pocket: listings, deals, contacts and documents. Download for Android or iPhone, or try the demo.',
            ],
        ],
        'contact' => [
            'meta_title' => [
                'Contact — CoreX OS',
                'Contact CoreX OS | Real Estate Software South Africa',
            ],
            'meta_description' => [
                'Get in touch with the CoreX OS team — questions, support, or anything else.',
                'Talk to the CoreX OS team about real estate software for your South African agency: a demo, pricing, support or moving from your current CRM.',
            ],
        ],
        'take-on' => [
            'meta_title' => [
                'Moving to CoreX — how take-on works',
                'Switch Your Estate Agent CRM, No Take-On Fee | CoreX OS',
            ],
            'meta_description' => [
                'How agencies move to CoreX OS: no take-on fee, your onboarding month free, and we handle the migration from your current system.',
                'Moving to CoreX OS costs nothing to start: no take-on fee, a free onboarding month, and we migrate your listings and contacts from your current CRM.',
            ],
        ],
    ];

    public function up(): void
    {
        $this->swap(from: 0, to: 1);
    }

    /**
     * The same rule in reverse: only a field still holding our new text goes
     * back, so an admin's edit made since is kept.
     */
    public function down(): void
    {
        $this->swap(from: 1, to: 0);
    }

    private function swap(int $from, int $to): void
    {
        foreach (self::CHANGES as $key => $fields) {
            foreach ($fields as $field => $values) {
                DB::table('pages')
                    ->where('key', $key)
                    ->where($field, $values[$from])
                    ->update([$field => $values[$to], 'updated_at' => now()]);
            }
        }
    }
};
