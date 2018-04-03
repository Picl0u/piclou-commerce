<?php
use \App\Http\Picl0u\CustomRoute;

Route::group([
    'middleware' => ['web','user'],
    'prefix' => 'compte',
    'namespace' => 'Modules\User\Http\Controllers'
], function() {

    // Mon compte
    Route::get('/', 'UserController@index')->name('user.account');
    // Mes informations
    Route::get('mes-informations', 'UserController@informations')->name('user.infos');
    Route::post('mes-informations/update', 'UserController@informationsUpdate')->name('user.infos.update');
    // Mes adresses
    Route::get('mes-adresses', 'UserController@addresses')->name('user.addresses');
    Route::get('mes-adresses/creation', 'UserController@addressesCreate')->name('user.addresses.create');
    Route::post('mes-adresses/store', 'UserController@addressesStore')->name('user.addresses.store');
    Route::get('mes-adresses/edition/{uuid}', 'UserController@addressesEdit')
        ->where(['uuid' => '[a-z-0-9\-]+'])
        ->name('user.addresses.edit');
    Route::post('mes-adresses/update/{uuid}', 'UserController@addressesUpdate')
        ->where(['uuid' => '[a-z-0-9\-]+'])
        ->name('user.addresses.update');
    Route::get('mes-adresses/delete/{uuid}', 'UserController@addressesDelete')
        ->where(['uuid' => '[a-z-0-9\-]+'])
        ->name('user.addresses.delete');

});

/* Administration */
Route::group([
    'middleware' => ['web','admin'],
    'prefix' => config('ikCommerce.adminUrl'),
    'namespace' => 'Modules\User\Http\Controllers'
], function(){

    /* Utilisateurs */
    CustomRoute::crud('users','AdminUserController', 'admin.users','user');
    /* Adresses */
    CustomRoute::crud('addresses','AdminUserAddressController', 'admin.addresses', 'user');


});
