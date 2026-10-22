<?php

namespace App\Support;

/**
 * The questions on the rental CRM page. The page renders them and its FAQPage
 * structured data is built from them, so the two always say the same thing.
 *
 * Every claim here must be one FeatureInventory's Rentals list backs up. In
 * particular CoreX does not handle money — no rent collection, trust account
 * or financial statements — and the first answer says so plainly, because an
 * AI comparison against rental accounting packages will ask.
 */
class RentalsFaq
{
    /**
     * [question, answer] pairs. Answers are HTML authored here, never user
     * input. $linkClass styles the links inside them on the page; structured
     * data strips the tags.
     *
     * @return list<array{0: string, 1: string}>
     */
    public static function items(string $linkClass = ''): array
    {
        return [
            ['Does CoreX collect rent or run a trust account?', 'No. CoreX is a rental CRM, not rental accounting software. It runs the letting workflow: applications, vetting, leases, inspections and maintenance. Rent collection, trust accounting and landlord or tenant statements stay in the accounting system your agency already uses.'],
            ['Is the rentals module an extra cost?', 'No. Every CoreX feature, rentals included, is on every plan. Pricing starts at R'.number_format(Pricing::lowestMonthly()).' a month for one agent. See the <a href="'.route('pricing').'" class="'.$linkClass.'">price list</a> for every band.'],
            ['Can tenants apply online?', 'Yes. Each applicant gets a private link to fill in a rental application, upload their documents and sign it. The form saves as they go, so they can finish it later.'],
            ['How do we vet applicants?', 'Your agency sets the affordability formula and chooses the credit bureau it uses for checks. Each application works through your own checklist, which can be required before a decision. Agents highlight parts of the documents and capture figures into a ledger that keeps every change visible, then approve or decline with your standard reasons.'],
            ['How do inspections work?', 'Agents run move-in and move-out inspections with photos, in your own room order and condition ratings. The tenant gets a private read-only link to the finished report. At move-out, CoreX puts move-in and move-out items and photos side by side so you can judge damage against the deposit. Scanned paper inspection forms can be read straight into an inspection.'],
            ['Can we manage maintenance in CoreX?', 'Yes. Log a fault at a rental property, turn it into a work order for a contractor, record and choose between supplier quotes, and track the job to completion.'],
            ['We also sell property. Is that a separate system?', 'No. Rentals sit in the same system as your listings, contacts, deals, portal feeds, e-signature and PPRA and FICA compliance. Tenants and landlords are contacts like any other, and lease expiry dates land on the same calendar.'],
            ['How do we move from our current system?', 'There is no take-on fee and your onboarding month is free. Our team exports your records from your current system and maps them into CoreX. See <a href="'.route('take-on').'" class="'.$linkClass.'">how take-on works</a>.'],
        ];
    }
}
