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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get( 'workouts', 'WorkoutsController@index' )->name('workouts.list');

Route::get( 'workouts/create', 'WorkoutsController@create' );

Route::get('/workout/{post}', 'WorkoutsController@show');

Route::post( 'workouts', 'WorkoutsController@store' );

Route::delete( 'workouts/{post}', 'WorkoutsController@destroy' );

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
