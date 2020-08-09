<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes([
    'register' => false,
    'verify'   => false
]);

Route::middleware('auth')->group(function () {
    Route::get('logout', ['uses' => 'Auth\LoginController@logout'])->name('logout');

    Route::get('/', 'HomeController@index')->name('home');

    Route::prefix('trainings')->group(function () {
        Route::get('/{type?}', 'TrainingsController@index')->name('trainings.index');
        Route::get('training-page/{training}', 'TrainingsController@show')->name('trainings.show');

        Route::get('{id}/file/{fileId}', 'FilesController@getFile')->name('training.files');
    });
});
