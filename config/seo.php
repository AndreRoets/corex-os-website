<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Noindex on hosts other than APP_URL's
    |--------------------------------------------------------------------------
    |
    | See App\Http\Middleware\NoindexForeignHosts. Production only unless this
    | is switched off — the tests switch it off to exercise the header.
    |
    */

    'noindex_foreign_hosts' => [
        'production_only' => env('SEO_NOINDEX_FOREIGN_HOSTS_PRODUCTION_ONLY', true),
    ],

];
