<?php

Route::group([
    'middleware' => 'web',
    'prefix' => 'editor',
    'namespace' => 'Modules\Editor\Http\Controllers'], function()
{
    Route::get('/', 'EditorController@index');
});
