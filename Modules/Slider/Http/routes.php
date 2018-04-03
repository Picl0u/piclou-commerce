<?php
use App\Http\Picl0u\CustomRoute;

Route::group(['middleware' => 'web', 'prefix' => 'slider', 'namespace' => 'Modules\Slider\Http\Controllers'], function()
{
    Route::get('/', 'SliderController@index');
});

/* Admin */
Route::group([
    'middleware' => ['web','admin'],
    'prefix' => config('ikCommerce.adminUrl'),
    'namespace' => 'Modules\Slider\Http\Controllers'
], function(){

    CustomRoute::crud('sliders','AdminSliderController', 'sliders', 'slider');
    Route::get("/sliders/positions",'AdminSliderController@positions')->name("sliders.positions");
    Route::post("/sliders/positionsStore", 'AdminSliderController@positionsStore')
        ->name("sliders.positions.store");

    /* Traductions */
    Route::any("/sliders/translate",'AdminSliderController@translate')
        ->name("admin.sliders.translate");

    /* Settings */
    Route::get('/settings/slider','AdminSettingsController@slider')->name("settings.slider");
    Route::post('/settings/slider/store','AdminSettingsController@storeSlider')->name("settings.slider.store");
});
