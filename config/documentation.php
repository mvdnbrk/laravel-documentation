<?php

return [

    'versions' => [
        'default' => env('DOCUMENTATION_DEFAULT_VERSION', null),
        'published' => [
            //
        ]
    ],

    'default_page' => env('DOCUMENTATION_DEFAULT_PAGE', 'installation'),

    'index_page' => env('DOCUMENTATION_INDEX', 'index'),

    'storage' => [
        'disk' => null,
    ],

    'excluded_pages' => [
        'readme',
    ],

];
