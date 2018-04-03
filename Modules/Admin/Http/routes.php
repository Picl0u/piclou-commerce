<?php
use \App\Http\Picl0u\CustomRoute;
Route::group([
    'middleware' => ['web','admin'],
    'prefix' => config('ikCommerce.adminUrl'),
    'namespace' => 'Modules\Admin\Http\Controllers'
], function() {


    Route::get('/','AdminController@dashboard')->name('admin.dashboard');

    Route::post('logout','AdminController@logout')->name('admin.logout');

    /* Administrateurs */
    CustomRoute::crud('admins','AdminUsersController','admin.admin', 'admin');

    /* Accèssible uniquement aux super admin */
    Route::group(['middleware' => ['role:'.config('ikCommerce.superAdminRole')]], function () {
        /* Roles */
        CustomRoute::crud('roles', 'RolesController', 'admin.roles');
        /* Permissions */
        Route::get('permissions', 'PermissionsController@index')->name('admin.permissions.index');
        Route::post('permissions/save', 'PermissionsController@savePermission')->name('admin.perssions.save');
    });

});

Route::group([
    'middleware' => ['web'],
    'prefix' => config('ikCommerce.adminUrl'),
    'namespace' => 'Modules\Admin\Http\Controllers'
], function() {

    Route::get('login','AdminController@login')->name('admin.login');

});

