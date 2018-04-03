<?php

Route::group([
    'middleware' => 'web',
    'prefix' => 'contact',
    'namespace' => 'Modules\Contact\Http\Controllers'
], function() {

    Route::get('/', 'ContactController@index')->name('contact.index');
    Route::post('/send', 'ContactController@send')->name('contact.send');

});
