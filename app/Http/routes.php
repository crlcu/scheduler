<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::auth();

Route::any('register', function () {
    return redirect()->action('Auth\AuthController@login');
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('/', 'TasksController@index');

	Route::resource('tasks', 'TasksController');

	Route::get('tasks/{id}/enable', 'TasksController@enable');
	Route::get('tasks/{id}/disable', 'TasksController@disable');
	Route::get('tasks/{id}/run', 'TasksController@run');
});
