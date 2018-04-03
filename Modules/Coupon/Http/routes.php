<?php
use \App\Http\Picl0u\CustomRoute;
Route::group([
    'middleware' => 'web',
    'prefix' => 'coupon',
    'namespace' => 'Modules\Coupon\Http\Controllers'
], function() {
    Route::get('/', 'CouponController@index');
});

/* Admin */
Route::group([
    'middleware' => ['web','admin'],
    'prefix' => config('ikCommerce.adminUrl'),
    'namespace' => 'Modules\Coupon\Http\Controllers'
], function(){

    CustomRoute::crud('coupons','AdminCouponController', 'admin.coupon', 'coupon');
});
