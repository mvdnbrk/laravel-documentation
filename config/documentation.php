<?php

return [

    'versions' => [
        'default' => env('DOCUMENTATION_DEFAULT_VERSION', null),
        'published' => [
            //
        ],
    ],

    'pages' => [
        'default' => env('DOCUMENTATION_DEFAULT_PAGE', 'installation'),
        'index' => env('DOCUMENTATION_INDEX', 'index'),
        'exclude' => [
            'readme',
        ],
    ],

    'storage' => [
        'disk' => null,
    ],

];
