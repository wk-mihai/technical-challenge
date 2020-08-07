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
    Route::get('logout', ['uses' => 'Auth\LoginController@logout'])->name('logout');

    Route::get('/', 'HomeController@index')->name('home');

    Route::middleware('can.view.trainings')->group(function () {
        Route::prefix('trainings')->group(function () {
            Route::get('/', 'TrainingsController@index')->name('trainings');
            Route::get('{id}/file/{fileId}', 'FilesController@getFile')->name('training.files');
        });
    });

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::prefix('trainings')->group(function () {
            Route::get('/', 'Admin\TrainingsController@index')->name('admin.trainings.index');
            Route::get('create', 'Admin\TrainingsController@create')->name('admin.trainings.create');
            Route::get('{training}/edit', 'Admin\TrainingsController@edit')->name('admin.trainings.edit');
            Route::get('{training}', 'Admin\TrainingsController@show')->name('admin.trainings.show');
            Route::post('/', 'Admin\TrainingsController@store')->name('admin.trainings.store');
            Route::patch('{training}', 'Admin\TrainingsController@update')->name('admin.trainings.update');
            Route::delete('{training}', 'Admin\TrainingsController@destroy')->name('admin.trainings.destroy');
        });
    });
});
