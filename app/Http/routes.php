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

Route::group(['middleware' => 'firewall'], function () {
    Route::auth();
    Route::any('register', 'Auth\AuthController@login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'TasksController@index');
    Route::get('tasks/timeline', 'TasksController@timeline');
    
    Route::resource('tasks', 'TasksController');

    Route::get('tasks/{id}/run', 'TasksController@run');
    Route::get('tasks/{id}/ping', 'TasksController@ping');
    Route::put('tasks/{id}/onoff', 'TasksController@onoff');
    Route::get('tasks/{id}/clear', 'TasksController@clear');

    Route::get('execution/{id}/stop', 'TaskExecutionsController@stop');
});

Route::group(['middleware' => ['auth', 'role:manage-roles']], function () {
    Route::resource('roles', 'RolesController');
});

Route::group(['middleware' => ['auth', 'role:manage-groups']], function () {
    Route::resource('groups', 'GroupsController');
});

Route::group(['middleware' => ['auth', 'role:manage-users']], function () {
    Route::resource('users', 'UsersController');
});

Route::group(['middleware' => ['auth', 'role:feature-notifications']], function () {
    Route::resource('notifications', 'TaskNotificationsController');
    Route::get('tasks/{id}/notifications', 'TasksController@notifications');
});

Route::get('unsubscribe/{id}', 'TaskNotificationsController@unsubscribe');
Route::get('api/docs', 'PagesController@docs');

Route::group(['middleware' => ['auth.basic', 'firewall'], 'namespace' => 'Api', 'prefix' => 'api'], function () {
    Route::put('tasks/{id}/onoff', 'TasksController@onoff');
    Route::get('tasks/{id}/run', 'TasksController@run');
});
