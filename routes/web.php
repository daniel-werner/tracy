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

Route::resource('workouts', 'WorkoutsController')->middleware('auth');
Route::group(['middleware' => 'can:admin'], function() {
    Route::resource('users', 'UsersController')->middleware('auth');
});

Route::get('/workout/{workout}', 'WorkoutsController@show_api');

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
