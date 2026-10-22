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

    /*
    |--------------------------------------------------------------------------
    | IndexNow
    |--------------------------------------------------------------------------
    |
    | Tells Bing (and through it ChatGPT search, Copilot and the other engines
    | that share IndexNow) the moment a page changes, instead of waiting for a
    | recrawl. See App\Support\IndexNow. The key is any 8–128 character
    | [A-Za-z0-9-] string; it is served at /{key}.txt to prove we own the host.
    | Unset, the key file 404s and nothing is ever submitted.
    |
    */

    'indexnow' => [
        'key' => env('INDEXNOW_KEY'),
        'endpoint' => env('INDEXNOW_ENDPOINT', 'https://api.indexnow.org/indexnow'),
    ],

];
