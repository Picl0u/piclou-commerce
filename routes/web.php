<?php

/* Authentification utilisateurs */
Auth::routes();

Route::get('/home', function () {
    return redirect('/compte');
});


/* Route pour les tests */
Route::get('debug/test', function(){

})->name('debug');

