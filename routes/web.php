<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], function() {
    Route::get('/workouts/search', 'WorkoutsController@search');
    Route::resource('workouts', 'WorkoutsController');
});

Route::group(['middleware' => 'can:admin'], function() {
    Route::resource('users', 'UsersController')->middleware('auth');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
