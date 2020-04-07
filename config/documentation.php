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
    | Here you may specify which page may be served a the default page.
    | The index page is where you have of the "table of contents"
    | for your documentation. This page is excluded from viewing
    | by default. You may also specify additional pages to be
    | excluded from viewing.
    |
    */

    'pages' => [
        'default' => 'installation',
        'index' => 'index',
        'exclude' => [
            'readme',
        ],
    ],

    'storage' => [
        'disk' => null,
    ],

];
