<?php
use App\Http\Picl0u\CustomRoute;

Route::group([
    'middleware' => 'web',
    'prefix' => 'newsletter',
    'namespace' => 'Modules\Newsletter\Http\Controllers'
], function(){

    Route::post('register', 'NewsletterController@register')->name('newsletter.register');

});

/* Administration */
Route::group([
    'middleware' => ['web','admin'],
    'prefix' => config('ikCommerce.adminUrl'),
    'namespace' => 'Modules\Newsletter\Http\Controllers'
], function(){


    CustomRoute::crud('newsletters', 'AdminNewsletterController', 'admin.newsletter', 'newsletter');

    Route::group(['middleware' => ['permission:access - newsletter']], function () {
        Route::get('newsletters/export', 'AdminNewsletterController@export')->name('admin.newsletter.export');
    });

    /* Contenus */
    CustomRoute::crud('newsletters/contents', 'AdminNewsletterContentController', 'admin.newsletter.content', 'newsletter');
    /* Traductions */
    Route::any("/newsletters/contents/translate",'AdminNewsletterContentController@translate')
        ->name("admin.newsletter.content.translate");
});

