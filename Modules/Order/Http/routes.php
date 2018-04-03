<?php

use App\Http\Picl0u\CustomRoute;

/* Compte client */
Route::group([
    'middleware' => ['web','user'],
    'prefix' => 'compte/commandes',
    'namespace' => 'Modules\Order\Http\Controllers'
], function() {

    /* Mon compte */
    Route::get('/', 'OrderController@index')->name('order.index');

    /* Facture */
    Route::get("facture/{uuid}",'OrderController@invoice')
        ->where(['uuid' => '[a-z-0-9\-]+'])
        ->name("order.invoice");

    /* Commande en détail */
    Route::get("detail/{uuid}",'OrderController@show')
        ->where(['uuid' => '[a-z-0-9\-]+'])
        ->name("order.show");

    /* Retour produit */
    Route::post('return/{uuid}','OrderController@returnProducts')
        ->where(['uuid' => '[a-z-0-9\-]+'])
        ->name("order.return");

});

/* Admin */
Route::group([
    'middleware' => ['web','admin'],
    'prefix' => config('ikCommerce.adminUrl'),
    'namespace' => 'Modules\Order\Http\Controllers'
], function(){

    Route::prefix('orders')->group(function () {

        /* Commandes */
        CustomRoute::crud('orders','AdminOrdersController', 'orders.orders', 'order');
        Route::group(['middleware' => ['permission:access - order']], function () {
            Route::get("/orders/orders/invoice/{uuid}", 'AdminOrdersController@getInvoice')
                ->where(['uuid' => '[a-z-0-9\-]+'])
                ->name("orders.orders.invoice");

            /* Factures */
            Route::get("/orders/orders/invoices", 'AdminOrdersController@invoices')->name("orders.invoices");
            Route::post("/orders/orders/invoices/export", 'AdminOrdersController@invoicesExport')
                ->name("orders.invoices.export");
            Route::get("/orders/orders/invoices/download/{uuid}", 'AdminOrdersController@invoicesDownload')
                ->where(['uuid' => '[a-z-0-9\-]+'])
                ->name("orders.invoices.download");
        });
        Route::group(['middleware' => ['permission:edit - order']], function () {
            /* Mettre à jours le statut de la commande */
            Route::post('orders/status/{uuid}', 'AdminOrdersController@statusUpdate')
                ->where(['uuid' => '[a-z-0-9\-]+'])
                ->name('orders.orders.status');

            /* Mettre à jours les infos du transporteur */
            Route::post('orders/carrier/{uuid}', 'AdminOrdersController@carrierUpdate')
                ->where(['uuid' => '[a-z-0-9\-]+'])
                ->name('orders.orders.carrier');
        });

        /* Statuts des commandes */
        CustomRoute::crud('status','AdminStatusController', 'orders.status','order');
        /* Traductions */
        Route::any("status/translate",'AdminStatusController@translate')
            ->name("admin.orders.status.translate");

        /* Transporteurs */
        CustomRoute::crud('carriers','AdminCarriersController', 'orders.carriers','order');

        /* Pays */
        CustomRoute::crud('countries','AdminCountriesController', 'orders.countries','order');
        Route::group(['middleware' => ['permission:edit - order']], function () {
            Route::get("/countries/activate/{id}", 'AdminCountriesController@activate')
                ->name("orders.countries.activate")
                ->where(['id' => '[0-9]+']);
            Route::get("/countries/desactivate/{id}", 'AdminCountriesController@desactivate')
                ->name("orders.countries.desactivate")
                ->where(['id' => '[0-9]+']);
        });

    });

    /* Settings  */
    Route::group(['middleware' => ['permission:edit - order']], function () {
        Route::get('/settings/orders', 'AdminSettingsController@orders')->name("settings.orders");
        Route::post('/settings/orders/store', 'AdminSettingsController@ordersStore')->name("settings.orders.store");
    });

});
