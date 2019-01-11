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

// Autentication routes
Route::post('login', 'AuthController@login');
Route::post('register', 'UserController@store');
Route::post('refresh', 'AuthController@refresh');

Route::group(['middleware' => 'jwt.auth',], function () {
    Route::get('logout', 'AuthController@logout');
    Route::get('user', 'AuthController@me');

    // User
    Route::get('me', 'UserController@show');
    Route::put('me', 'UserController@update');

    // Project
    Route::resource('projects', 'ProjectController');
    Route::post('projects/filter', 'ProjectController@filter');
    Route::post('projects/{id}/finish', 'ProjectController@finish');
    Route::post('projects/{id}/back', 'ProjectController@back');

});
