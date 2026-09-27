<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CoreX OS API
    |--------------------------------------------------------------------------
    |
    | CoreX OS is the source of truth for webinars, registrants and the demo
    | credentials each registrant is issued. This website stores none of it —
    | every read and write below goes over the wire and straight back out to
    | the page. See app/Services/CoreX/WebinarClient.php.
    |
    */

    'base_url' => rtrim((string) env('COREX_API_BASE', 'https://corexos.co.za'), '/'),

    /*
    | Two tokens, deliberately. The public page may only register people; the
    | admin console may also read registrant PII and mint webinars. Splitting
    | them means a compromise of the public path cannot leak the registrant
    | list. Both are server-side only and must never reach a browser.
    */
    'public_token' => env('COREX_WEBINAR_PUBLIC_TOKEN'),
    'admin_token' => env('COREX_WEBINAR_ADMIN_TOKEN'),

    'timeout' => (int) env('COREX_API_TIMEOUT', 15),

    /*
    | CSV downloads can be large, and the connection is held open while CoreX
    | streams. A short timeout would truncate a big export mid-file.
    */
    'download_timeout' => (int) env('COREX_API_DOWNLOAD_TIMEOUT', 120),

    /*
    | CoreX is mid-way through splitting its single `name` field into
    | `first_name` / `last_name`. Until that lands, the register endpoint
    | requires `name` and knows nothing of the other two. We always send the
    | split pair (the form has collected them from day one) and, while this is
    | true, also send the joined `name` so the call satisfies today's
    | validation. Flip to false once CoreX has switched — no redeploy dance,
    | either payload is valid during the changeover.
    */
    'send_legacy_name' => (bool) env('COREX_WEBINAR_SEND_LEGACY_NAME', true),

    /*
    | Every webinar time is South African. SAST has no daylight saving, so the
    | offset is a constant +02:00 — but we still name the zone rather than
    | hard-coding the offset, so Carbon does the formatting.
    */
    'timezone' => 'Africa/Johannesburg',

    /*
    | Where someone who arrives too late is told to write.
    |
    | A closed registration page is a warm lead standing at a locked door. The
    | address is on that page so the answer to "can I still come?" is one click,
    | not a search for a contact form.
    */
    'contact_email' => env('COREX_CONTACT_EMAIL', 'info@corexweb.co.za'),

    /*
    |--------------------------------------------------------------------------
    | Mobile app
    |--------------------------------------------------------------------------
    |
    | The two store listings, and the shared demo login the /mobile-app page
    | hands out once someone has left their details.
    |
    | The login is deliberately NOT a secret: it is a read-mostly sandbox
    | account in CoreX, seeded with demo data, and the whole point is that we
    | give it away. It lives in config rather than in a template so rotating it
    | is an env change, and so the page and the email that follows can never
    | disagree about what the password is.
    |
    */

    'mobile_app' => [
        'android_url' => env('COREX_APP_ANDROID_URL', 'https://play.google.com/store/apps/details?id=za.co.corex_mobile&hl=en_ZA'),
        'ios_url' => env('COREX_APP_IOS_URL', 'https://apps.apple.com/za/app/corex-os/id6792303320'),

        'demo_email' => env('COREX_APP_DEMO_EMAIL', 'Demo@corexweb.co.za'),
        'demo_password' => env('COREX_APP_DEMO_PASSWORD', 'Corex@mobiledemo'),
    ],

];
