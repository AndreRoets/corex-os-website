<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The pricing page moved from two plans (CoreX Team / CoreX Agency) to a
     * single ladder of agent bands. The stored meta description overrides the
     * template's, so on a running install it would keep quoting the old plans
     * to Google and in link previews.
     *
     * PageSeeder says the same thing for fresh installs; this is for the ones
     * already running. The metadata is editable from the admin panel, so only
     * replace a field where it still names the old plans.
     */
    private const DESCRIPTION = 'One price per band, unlimited agents within it. From R450 a month for one agent to R5,095 for up to 30, set automatically by your active agents. No setup fees.';

    public function up(): void
    {
        $page = DB::table('pages')->where('key', 'pricing')->first();

        if ($page === null) {
            return;
        }

        $changes = [];

        foreach (['meta_description', 'og_description', 'twitter_description'] as $field) {
            $value = (string) ($page->{$field} ?? '');

            if (str_contains($value, 'CoreX Team') || str_contains($value, 'CoreX Agency')) {
                $changes[$field] = self::DESCRIPTION;
            }
        }

        if ($changes !== []) {
            DB::table('pages')->where('key', 'pricing')->update($changes);
        }
    }

    public function down(): void
    {
        // The old plans are withdrawn; there is nothing to restore them to.
    }
};
