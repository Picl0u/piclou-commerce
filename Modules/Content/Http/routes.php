<?php
use App\Http\Picl0u\CustomRoute;

/* Page de contenu */
Route::group(['middleware' => 'web', 'prefix' => 'content', 'namespace' => 'Modules\Content\Http\Controllers'], function()
{
    Route::get('{slug}-{id}','ContentController@index')
        ->name('content.index')
        ->where(['slug' => '[a-z-0-9\-]+', 'id' => '[0-9]+']);
});

/* Admin */
Route::group([
    'middleware' => ['web','admin'],
    'prefix' => config('ikCommerce.adminUrl'),
    'namespace' => 'Modules\Content\Http\Controllers'
], function(){

    /* Catégories */
    CustomRoute::crud('pages/categories','AdminCategoriesController', 'admin.pages.categories', 'content');
    /* Traductions */
    Route::any("/pages/categories/translate",'AdminCategoriesController@translate')
        ->name("admin.pages.categories.translate");

    /* Contenus */
    CustomRoute::crud('pages/contents','AdminContentsController', 'admin.pages.contents', 'content');
    /* Traductions */
    Route::any("/pages/contents/translate",'AdminContentsController@translate')
        ->name("admin.pages.contents.translate");
    /* Positions */
    Route::get("/pages/contents/positions",'AdminContentsController@positions')->name("admin.pages.contents.positions");
    Route::post("/pages/contents/positionsStore", 'AdminContentsController@positionsStore')
        ->name("admin.pages.contents.positions.store");


});

