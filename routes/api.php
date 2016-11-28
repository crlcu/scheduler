<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth.basic'], 'namespace' => 'Api'], function () {
    // Controllers Within The "App\Http\Controllers\Api" Namespace

    Route::group(['middleware' => 'role:manage-roles'], function () {
        Route::resource('roles', 'RolesController');
    });
});
