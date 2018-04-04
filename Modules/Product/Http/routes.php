<?php

use App\Http\Picl0u\CustomRoute;

Route::group([
    'middleware' => 'web',
    'prefix' => config('ikCommerce.productPrefix'),
    'namespace' => 'Modules\Product\Http\Controllers'],
    function(){

        /* Produits */
        Route::get('{slug}-{id}','ProductController@lists')
            ->name('product.list')
            ->where(['slug' => '[a-z-0-9\-]+', 'id' => '[0-9]+']);

        /* Détail d'un produit */
        Route::get(__('shop.product').'/{slug}-{id}','ProductController@show')
            ->name('product.show')
            ->where(['slug' => '[a-z-0-9\-]+', 'id' => '[0-9]+']);

        /* Poster un commentaire */
        Route::post(__('shop.product').'comment/{uuid}','ProductController@addComment')
            ->name('product.comment')
            ->where(['uuid' => '[a-z-0-9\-]+'])
            ->middleware('user');

        /* Recherche */
        Route::get('recherche','ProductController@search')->name('product.search');

        /* Ventes Flash */
        Route::get('ventes-flash','ProductController@flashSales')->name('product.flash');
});

/* Admin */
Route::group([
    'middleware' => ['web','admin'],
    'prefix' => config('ikCommerce.adminUrl'),
    'namespace' => 'Modules\Product\Http\Controllers'
], function(){
    Route::prefix('shop')->group(function () {

        /* Produits */
        CustomRoute::crud('products','AdminProductController', 'shop.products', 'product');

        /* Déclinaisons - Affichage du formulaire */
        Route::get('products/attributes/add/{id}', 'AdminProductController@declinaison')
            ->name('admin.products.attribute.add')
            ->where(['id' => '[0-9]+']);
        /* Déclinaisons - Ajout d'une déclinaisons */
        Route::post('products/attributes/store/{id}', 'AdminProductController@declinaisonStore')
            ->name('admin.products.attribute.store')
            ->where(['id' => '[0-9]+']);
        /* Déclinaisons - Modifier une déclinaison */
        Route::get('products/attributes/edit/{id}/{uuid}', 'AdminProductController@declinaisonEdit')
            ->name('admin.products.attribute.edit')
            ->where([
                'id' => '[0-9]+',
                'uuid' => '[a-z-0-9\-]+'
            ]);
        /* Déclinaisons - Modifier une déclinaison */
        Route::post('products/attributes/update/{uuid}', 'AdminProductController@declinaisonUpdate')
            ->name('admin.products.attribute.update')
            ->where([
                'uuid' => '[a-z-0-9\-]+'
            ]);
        /* Déclinaisons - Supprimer une déclinaison */
        Route::get('products/attributes/delete/{uuid}', 'AdminProductController@declinaisonDelete')
            ->name('admin.products.attribute.delete')
            ->where([
                'uuid' => '[a-z-0-9\-]+'
            ]);
        Route::group(['middleware' => ['permission:edit - product']], function () {

            /* Images */
            Route::get("/products/image/delete/{id}", 'AdminProductController@imageDelete')
                ->name("shop.products.image.delete")
                ->where(['id' => '[0-9]+']);
            Route::post("/products/image/update/{id}", 'AdminProductController@imageUpdate')
                ->name("shop.products.image.update")
                ->where(['id' => '[0-9]+']);
            Route::post("/products/images/positions/{id}", 'AdminProductController@imagesPositions')
                ->name("shop.products.images.positions")
                ->where(['id' => '[0-9]+']);
            Route::post("/products/images/update/{id}", 'AdminProductController@imagesUpdate')
                ->name("shop.products.images.update")
                ->where(['id' => '[0-9]+']);

            /* Positions */
            Route::get("/products/positions", 'AdminProductController@positions')->name("shop.products.positions");
            Route::post("/products/positionsStore", 'AdminProductController@positionsStore')
                ->name("shop.products.positions.store");

            /* Traductions */
            Route::any("products/translate",'AdminProductController@translate')
                ->name("admin.shop.products.translate");

            /* Settings */
            Route::get('/settings/products','AdminSettingsController@products')->name("settings.products");
            Route::post('/settings/products/store','AdminSettingsController@storeProducts')
                ->name("settings.products.store");

        });

        Route::group(['middleware' => ['permission:create - product|permission:edit - product']], function () {
            Route::get("/products/imports", 'AdminProductController@import')->name("shop.products.imports");
            Route::post("/products/imports/store", 'AdminProductController@storeImport')
                ->name("shop.products.imports.store");

            Route::get("/products/export", 'AdminProductController@export_product')
                ->name("shop.products.export");

            Route::get("/products/export/attributes", 'AdminProductController@export_attributes')
                ->name("shop.products.export.attributes");

            Route::get("/products/imports/attributes", 'AdminProductController@import_attributes')
                ->name("shop.products.attributes.imports");
            Route::post("/products/imports/attributes/store", 'AdminProductController@import_attributes_store')
                ->name("shop.products.imports.attributes.store");
        });

        /* Catégories */
        CustomRoute::crud('categories','AdminCategoriesController', 'shop.categories', 'product');

        Route::group(['middleware' => ['permission:edit - product']], function () {

            Route::get("/categories/positions", 'AdminCategoriesController@positions')
                ->name("shop.categories.positions");
            Route::post("/categories/positionsStore", 'AdminCategoriesController@positionsStore')
                ->name("shop.categories.positions.store");

            Route::get("/categories/image/delete/{id}", 'AdminCategoriesController@imageDelete')
                ->name("shop.categories.image.delete")
                ->where(['id' => '[0-9]+']);

            Route::get("/categories/imageList/delete/{id}", 'AdminCategoriesController@imageListDelete')
                ->name("shop.categories.imageList.delete")
                ->where(['id' => '[0-9]+']);

            /* Traductions */
            Route::any("categories/translate",'AdminCategoriesController@translate')
                ->name("admin.shop.categories.translate");
        });


        /* Commentaires */
        CustomRoute::crud(
            'comments',
            'AdminCommentsController',
            'admin.products.comments',
            'product'
        );


    });
});

