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

Route::get('token', [
    'uses' => 'TokenController@create',
    'as' => 'token'
]);

Route::post('token', [
    'uses' => 'TokenController@store'
]);

Route::get('login/{token}', [
    'uses' => 'LoginController@login',
    'as' => 'login'
]);