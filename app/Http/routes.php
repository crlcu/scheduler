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
    Route::get('tasks/timeline', 'TasksController@timeline');
    
    Route::resource('tasks', 'TasksController');

    Route::get('tasks/{id}/run', 'TasksController@run');
    Route::get('tasks/{id}/enable', 'TasksController@enable');
    Route::get('tasks/{id}/disable', 'TasksController@disable');
    Route::get('tasks/{id}/clear', 'TasksController@clear');
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
    Route::resource('notifications', 'NotificationsController');
    Route::get('tasks/{id}/notifications', 'TasksController@notifications');
});
