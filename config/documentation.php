<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Documentation versions.
    |--------------------------------------------------------------------------
    |
    | Here you may specify all published versions of your documentation
    | and which version may serve as the default or latest version.
    |
    */

    'versions' => [
        'default' => '',
        'published' => [
            //
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Pages.
    |--------------------------------------------------------------------------
    |
    | Here you may specify which page may be served as the default page
    | and where your "table of contents" of your documentations lives.
    | You may also specify which pages are excluded from viewing.
    | The "table of contents" is excluded by default.
    |
    */

    'pages' => [
        'table_of_contents' => 'index',
        'default' => 'installation',
        'exclude' => [
            'readme',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage disk.
    |--------------------------------------------------------------------------
    |
    */

    'storage' => [
        'disk' => null,
    ],

];
