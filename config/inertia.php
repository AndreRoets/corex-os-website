<?php

/*
| Only what differs from the package's own config/inertia.php, which Laravel
| merges underneath this file key by key. The package looks for page
| components in resources/js/pages; ours live in resources/js/Pages, and on a
| case-sensitive filesystem that difference made every assertInertia() fail
| with "page component file does not exist".
*/

return [

    'pages' => [

        'ensure_pages_exist' => false,

        'paths' => [
            resource_path('js/Pages'),
        ],

        'extensions' => ['js', 'jsx', 'svelte', 'ts', 'tsx', 'vue'],

    ],

    'testing' => [

        'ensure_pages_exist' => true,

    ],

];
