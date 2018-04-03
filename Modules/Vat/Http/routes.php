<?php
use App\Http\Picl0u\CustomRoute;

/* Admin */
Route::group([
    'middleware' => ['web','admin'],
    'prefix' => config('ikCommerce.adminUrl'),
    'namespace' => 'Modules\Vat\Http\Controllers'
], function(){

    CustomRoute::crud('shop/vats','AdminVatController', 'shop.vats', 'vat');
});

