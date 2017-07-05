<?php

//Registro

Route::get('register', [
    'uses' => 'RegisterController@create',
    'as' => 'register'
]);

Route::post('register', [
    'uses' => 'RegisterController@store'
]);

Route::get('register_confirmation', function() {
    return view('register/confirmation');
})->name('register_confirmation');

//Login

Route::get('login', [
    'uses' => 'LoginController@create',
    'as' => 'login'
]);

Route::post('login', [
    'uses' => 'LoginController@store'
]);