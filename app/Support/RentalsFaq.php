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
            ['Does CoreX collect rent or run a trust account?', 'No. CoreX is a rental CRM, not rental accounting software. It runs the letting workflow: applications, leases, notices, inspections and maintenance. Rent collection, arrears, trust accounting, payments and landlord or tenant statements stay in reOS or the accounting system your agency already uses. CoreX keeps that system\'s payment reference on each lease.'],
            ['Is the rentals module an extra cost?', 'No. Every CoreX feature, rentals included, is on every plan. Pricing starts at R'.number_format(Pricing::lowestMonthly()).' a month for one agent. See the <a href="'.route('pricing').'" class="'.$linkClass.'">price list</a> for every band.'],
            ['Can tenants apply online?', 'Yes. Each applicant gets a private link to fill in a rental application, upload their documents and sign the declaration and credit check consent. The form saves as they go, and you can ask for a one-time PIN or an ID check before they come back to it. Your agency chooses which fields are shown and required, and can add its own.'],
            ['How do we vet applicants?', 'Each application works through your own checklist, which can be required before a decision. Agents highlight the applicant\'s documents and capture income and expense figures, with a monthly income worked out from the statement period. Named approvers then approve, decline with your standard reasons, ask for more information or return it. CoreX does not run credit checks or give an affordability verdict: the applicant signs consent naming your bureau, your agent runs the check and uploads the result, and the decision stays with your approvers.'],
            ['What lease notices can we send?', 'Breach notices (for payment and other breaches), notices to vacate and the Consumer Protection Act lease-expiry notice, each sent, resent, downloaded and archived from the lease. Breach notices can go out in bulk and be marked remedied or vacated. Notices the tenant gives are recorded and accepted or rejected.'],
            ['How do inspections work?', 'Agents run move-in, move-out, routine and interim inspections with photos, paired automatically, in your own room order and condition ratings. Inspections are signed through a link with reminders, and the tenant gets a private read-only report link. At move-out, CoreX puts move-in and move-out items side by side with proposed findings, and you mark which are charges to the tenant. CoreX does not calculate deductions. Scanned paper inspection forms can be read in, tick-marks only.'],
            ['Can we manage maintenance in CoreX?', 'Yes. Faults are logged by your staff, or by tenants and landlords in the client app, and turned into work orders. Contractors respond, quote, upload photos and invoices and mark the job done through a secure link without logging in. Landlords approve quotes and variations above your spending limit, the tenant confirms the work is done, and each job records who pays.'],
            ['We also sell property. Is that a separate system?', 'No. Rentals sit in the same system as your listings, contacts, deals, portal feeds, e-signature and PPRA and FICA compliance. Tenants and landlords are contacts like any other, and lease expiry dates land on the same calendar.'],
            ['How do we move from our current system?', 'There is no take-on fee and your onboarding month is free. Existing rentals come across in a bulk import, including from reOS, and our team maps your other records into CoreX. See <a href="'.route('take-on').'" class="'.$linkClass.'">how take-on works</a>.'],
        ];
    }
}
