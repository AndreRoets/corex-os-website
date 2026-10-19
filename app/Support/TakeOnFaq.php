<?php

namespace App\Support;

/**
 * The questions on the take-on page. The page renders them and its FAQPage
 * structured data is built from them, so the two always say the same thing.
 */
class TakeOnFaq
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
            ['Is there really no take-on fee?', 'Yes. There is no setup, onboarding or migration fee. Your onboarding month is free too.'],
            ['When does billing start?', 'On the first day of the month after your free onboarding month. Sign up in September, October is free, and your first billed month is November. CoreX is billed monthly in advance, based on your active users that month. See the <a href="'.route('pricing').'" class="'.$linkClass.'">price list</a> for current rates.'],
            ['Do I need to cancel my Property24 or Private Property subscription?', 'No. You only give notice to your current CRM or listing feed provider. Your portal subscriptions stay with the portals; CoreX simply becomes the system that feeds your listings to them.'],
            ["Why can't my listings go to the portals during a trial?", "Property24 and Private Property don't allow the same listing to arrive from two different systems. While your current CRM is still feeding them, CoreX can't. Once you've left, we switch your feeds over."],
            ['What data do you bring across?', "Your listings come from Property24, which supplies the master listing data for every agency. Contacts and other records are exported from your current system and mapped into CoreX by our team. You don't fill in import spreadsheets."],
            ['How long does the move take?', "The listing import itself takes hours, not weeks. Most of the onboarding month is setup, document templates and training, and waiting on Property24 to release your data once you've signed."],
            ['Am I locked into a long contract?', "No. CoreX runs month to month after sign-up, with 30 days' written notice to cancel."],
        ];
    }
}
