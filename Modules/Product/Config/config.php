<?php

return [
    'name' => 'Product',
    'admin.navigation' => [
        'sale' => [
            \Modules\Product\Http\Navigation\AdminProductNavigation::class
        ],
        'configuring' => [
            \Modules\Product\Http\Navigation\AdminSettingsNavigation::class
        ]
    ]
];
