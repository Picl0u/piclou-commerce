<?php

return [
    'name' => 'Slider',
    'admin.navigation' => [
        'personalize' => [
            \Modules\Slider\Http\Navigation\AdminSliderNavigation::class
        ],
        'configuring' => [
            \Modules\Slider\Http\Navigation\AdminSettingsNavigation::class
        ]
    ]
];
