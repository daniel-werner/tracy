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

/* Only logged in users have access to these routes */
Route::group(['middleware' => 'auth'], function() {
    Route::get('/workouts/search', 'WorkoutsController@search');
    Route::resource('workouts', 'WorkoutsController');
    Route::get('/users/profile', 'UsersController@profile')->name('profile');
    Route::put('/users/profile', 'UsersController@profile_update');
});

/* Only admins have access to these routes */
Route::group(['middleware' => 'can:admin'], function() {
    Route::resource('users', 'UsersController')->middleware('auth');
    Route::get('/workouts/geo_mock/{id}', 'WorkoutsController@geo_mock');
});

Auth::routes();

/* Guest users have access to these routes */
Route::get('/', 'HomeController@index')->name('home');
Route::get('/demo', 'Auth\LoginController@demo')->name('demo_login');
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');
