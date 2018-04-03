<?php

return [
    'name' => 'Order',
    'admin.widget' => [
        \Modules\Order\Http\Widgets\OrderWidget::class,
        \Modules\Order\Http\Widgets\BestSaleWidget::class
    ],
    'admin.navigation' => [
        'sale' => [
            \Modules\Order\Http\Navigation\AdminOrderNavigation::class
        ],
        'configuring' => [
            \Modules\Order\Http\Navigation\AdminSettingsNavigation::class
        ]
    ]
];
