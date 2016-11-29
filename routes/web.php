<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();

# Get route for logout
Route::get('logout', 'Auth\LoginController@logout');


Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'TasksController@search');
});
