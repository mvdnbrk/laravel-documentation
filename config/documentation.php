<?php

return [

    'versions' => [
        //
    ],

    'default_version' => env('DOCUMENTATION_DEFAULT_VERSION', null),

    'index_page' => env('DOCUMENTATION_INDEX', 'index'),

    'storage' => [
        'disk' => null,
    ],

    'excluded_pages' => [
        'readme',
    ],

];
