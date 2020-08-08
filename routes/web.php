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

    Route::middleware('can.view.trainings')->group(function () {
        Route::prefix('trainings')->group(function () {
            Route::get('/{type?}', 'TrainingsController@index')->name('trainings.index');
            Route::get('training-page/{training}', 'TrainingsController@show')->name('trainings.show');

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

        Route::prefix('types')->group(function () {
            Route::get('/', 'Admin\TypesController@index')->name('admin.types.index');
            Route::get('create', 'Admin\TypesController@create')->name('admin.types.create');
            Route::get('{type}/edit', 'Admin\TypesController@edit')->name('admin.types.edit');
            Route::get('{type}', 'Admin\TypesController@show')->name('admin.types.show');
            Route::post('/', 'Admin\TypesController@store')->name('admin.types.store');
            Route::patch('{type}', 'Admin\TypesController@update')->name('admin.types.update');
            Route::delete('{type}', 'Admin\TypesController@destroy')->name('admin.types.destroy');
        });

        Route::prefix('users')->group(function () {
            Route::get('/', 'Admin\UsersController@index')->name('admin.users.index');
            Route::get('create', 'Admin\UsersController@create')->name('admin.users.create');
            Route::get('{user}/edit', 'Admin\UsersController@edit')->name('admin.users.edit');
            Route::get('{user}', 'Admin\UsersController@show')->name('admin.users.show');
            Route::post('/', 'Admin\UsersController@store')->name('admin.users.store');
            Route::patch('{user}', 'Admin\UsersController@update')->name('admin.users.update');
            Route::delete('{user}', 'Admin\UsersController@destroy')->name('admin.users.destroy');
        });

        Route::prefix('roles')->group(function () {
            Route::get('/', 'Admin\RolesController@index')->name('admin.roles.index');
            Route::get('create', 'Admin\RolesController@create')->name('admin.roles.create');
            Route::get('{role}/edit', 'Admin\RolesController@edit')->name('admin.roles.edit');
            Route::get('{role}', 'Admin\RolesController@show')->name('admin.roles.show');
            Route::post('/', 'Admin\RolesController@store')->name('admin.roles.store');
            Route::patch('{role}', 'Admin\RolesController@update')->name('admin.roles.update');
            Route::delete('{role}', 'Admin\RolesController@destroy')->name('admin.roles.destroy');
        });
    });
});
