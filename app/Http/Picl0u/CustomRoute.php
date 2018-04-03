<?php
namespace App\Http\Picl0u;

use Illuminate\Support\Facades\Route;

class CustomRoute
{
    /**
     * Permet de générer le CRUD propre au système
     * @param string $uri
     * @param string $controller
     * @param string $name
     */
    public static function crud(string $uri, string $controller, string $name, $moduleName = null)
    {
        /* Liste */
        Route::group(['middleware' => ['permission:access - '.$moduleName]], function () use($controller, $name, $uri) {
            Route::get("/{$uri}", "{$controller}@index")
                ->name($name . ".index");
        });
        /* Création */
        Route::group(['middleware' => ['permission:create - '.$moduleName]], function () use($controller, $name, $uri) {

            Route::get("/{$uri}/create","{$controller}@create")
                ->name($name . ".create");

            Route::post("/{$uri}/create","{$controller}@store")
                ->name($name . ".store");
        });

        Route::group(['middleware' => ['permission:edit - '.$moduleName]], function () use($controller, $name, $uri) {
            /* Edition */
            Route::get("/{$uri}/edit/{uuid}","{$controller}@edit")
                ->name($name . ".edit")
                ->where(['uuid' => '[a-z-0-9\-]+']);
            /* Edition - POST */
            Route::post("/{$uri}/update/{uuid}","{$controller}@update")
                ->name($name . ".update")
                ->where(['uuid' => '[a-z-0-9\-]+']);
        });
        /* Suppression */
        Route::group(['middleware' => ['permission:delete - '.$moduleName]], function () use($controller, $name, $uri) {
            Route::get("/{$uri}/delete/{uuid}","{$controller}@destroy")
                ->name($name . ".delete")
                ->where(['uuid' => '[a-z-0-9\-]+']);
        });


    }
}