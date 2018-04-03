<?php

Route::group([
    'middleware' => ['web','user'],
    'prefix' => 'liste-de-souhait',
    'namespace' => 'Modules\Whishlist\Http\Controllers'
], function() {

    Route::get('/', 'WhishlistController@index')->name('whishlist.index');

    Route::get('ajouter-panier/{rowId}', 'WhishlistController@addCart')
        ->where(['rowId' => '[a-z-0-9\-]+'])
        ->name('whishlist.addCart');
});

Route::group([
    'middleware' => 'web',
    'prefix' => 'liste-de-souhait',
    'namespace' => 'Modules\Whishlist\Http\Controllers'
], function() {
    /* Ajouter un produit */
    Route::post('ajouter-produit', 'WhishlistController@addProduct')->name('whishlist.product.add');
});

