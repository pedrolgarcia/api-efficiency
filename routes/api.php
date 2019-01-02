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

Route::resource('profile', 'UserController');

Route::post('login', 'AuthController@login');
Route::post('register', 'UserController@store');
Route::post('refresh', 'AuthController@refresh');

Route::group(['middleware' => 'jwt.auth',], function () {
    Route::get('logout', 'AuthController@logout');
    Route::get('me', 'AuthController@me');

});
