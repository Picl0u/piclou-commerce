<?php

return [
    'name' => 'Coupon',
    'admin.navigation' => [
        'sale' => [
            \Modules\Coupon\Http\Navigation\AdminCouponNavigation::class
        ]
    ]
];
