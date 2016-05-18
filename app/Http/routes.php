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

Route::any('register', 'Auth\AuthController@login');

Route::group(['middleware' => 'auth'], function () {
	Route::get('/', 'TasksController@index');

	Route::resource('tasks', 'TasksController');

	Route::get('tasks/{id}/notifications', 'TasksController@notifications');
	Route::get('tasks/{id}/run', 'TasksController@run');
	Route::get('tasks/{id}/enable', 'TasksController@enable');
	Route::get('tasks/{id}/disable', 'TasksController@disable');
	Route::get('tasks/{id}/clear', 'TasksController@clear');

	Route::resource('notifications', 'NotificationsController');

	Route::resource('roles', 'RolesController');
	Route::resource('groups', 'GroupsController');
	Route::resource('users', 'UsersController');
});
