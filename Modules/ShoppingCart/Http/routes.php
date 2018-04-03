<?php

/* Panier */
Route::group([
    'middleware' => 'web',
    'prefix' => 'panier',
    'namespace' => 'Modules\ShoppingCart\Http\Controllers'
], function() {
    /* Ajouter un produit */
    Route::post('ajouter-produit', 'ShoppingCartController@addProduct')->name('cart.product.add');

    /* Modifier un produit */
    Route::post('edit', 'ShoppingCartController@editProduct')->name('cart.product.edit');

    /* Afficher le panier */
    Route::get('/', 'ShoppingCartController@show')->name('cart.show');

    /* Code promo */
    Route::post('coupon', 'ShoppingCartController@coupon')->name('cart.coupon');
    /* Code promo - Annuler */
    Route::get('coupon/cancel', 'ShoppingCartController@couponCancel')->name('cart.coupon.cancel');
    /* Code promo - Check */
    Route::get('coupon/check', 'ShoppingCartController@checkCoupon')->name('cart.coupon.check');
});

/* Commande */
Route::group([
    'middleware' => 'web',
    'prefix' => 'commande',
    'namespace' => 'Modules\ShoppingCart\Http\Controllers'
], function() {

    /* Connexion / Inscription */
    Route::get('connexion-inscription', 'ShoppingCartController@orderUser')->name('cart.user.connect');
    /* Utilisateur - Commande express */
    Route::post('user-express', 'ShoppingCartController@orderUserExpress')->name('cart.user.express');

    Route::group([
        'middleware' => 'cart',
    ], function() {
        /* Adresse */
        Route::get('/adresses', 'ShoppingCartController@orderAddresses')->name('cart.user.address');
        Route::post('/address-add', 'ShoppingCartController@orderAddressStore')->name('cart.user.address.store');
        Route::post('address-select', 'ShoppingCartController@orderAddressSelect')->name('cart.user.address.select');

        /* Transpoteurs */
        Route::get('transpoteurs', 'ShoppingCartController@orderShipping')->name('cart.user.shipping');
        Route::post('ctranspoteurs/store', 'ShoppingCartController@orderShippingStore')->name('cart.user.shipping.store');

        /* Récapitulatif */
        Route::get('recapitulatif', 'ShoppingCartController@orderRecap')->name('cart.recap');

        /* Process */
        Route::get('process', 'ShoppingCartController@process')->name('cart.process');
    });

    /* Retour */
    Route::any('return', 'ShoppingCartController@orderReturn')
        ->name('cart.return');
    Route::any('return-test', 'ShoppingCartController@orderTest')
        ->name('cart.return.test');
    /* Annulé/Refusé */
    Route::any('cancel', 'ShoppingCartController@orderCancel')
        ->name('cart.cancel');
    /* Accepté */
    Route::any('accept', 'ShoppingCartController@orderAccept')
        ->name('cart.accept');
});
