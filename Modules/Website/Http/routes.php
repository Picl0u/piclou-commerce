<?php

Route::group([
    'middleware' => 'web',
    'prefix' => '',
    'namespace' => 'Modules\Website\Http\Controllers'
], function() {

    /* Homepage */
    Route::get('/', 'WebsiteController@homepage')->name('homepage');

    /* Choix de la langue */
    Route::get('locale/{locale}',  'WebsiteController@setLocale')
        ->where(['locale' => '[a-z]+'])
        ->name('change.language');

    /* Vider le cache fichier */
    Route::get('/clear-cache', function() {
        Artisan::call('cache:clear');
        return redirect()->route('homepage');
    });

    /* Erreur 404 */
    Route::get('erreur-404', 'WebsiteController@notFound')->name('error.404');
    /* Erreur 500 */
    Route::get('erreur-500', 'WebsiteController@fatalError')->name('error.500');

});

/* Admin */
Route::group([
    'middleware' => ['web','admin'],
    'prefix' => config('ikCommerce.adminUrl'),
    'namespace' => 'Modules\Website\Http\Controllers'
], function(){

    /* Settings  */
    Route::group(['middleware' => ['permission:edit - website']], function () {
        Route::get('/settings/generals', 'AdminSettingsController@generals')->name("settings.generals");
        Route::post('/settings/generals/store', 'AdminSettingsController@storeGenerals')->name("settings.generals.store");
    });
});
