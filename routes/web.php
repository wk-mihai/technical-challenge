<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'register' => false,
    'verify'   => false
]);

Route::middleware('auth')->group(function () {
    Route::get('/logout', ['uses' => 'Auth\LoginController@logout'])->name('logout');

    Route::get('/', 'HomeController@index')->name('home');

    Route::middleware('can.view.trainings')->group(function () {
        Route::get('/trainings', 'HomeController@index')->name('trainings');
    });
});
