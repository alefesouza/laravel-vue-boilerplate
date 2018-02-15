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

Route::group([
    'middleware' => 'api',
    'namespace' => 'Auth',
    'prefix' => 'auth',
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('user', 'AuthController@user');

    Route::post('register', 'RegisterController@register');
});

Route::group([
    'middleware' => ['jwt.auth'],
], function () {
    Route::get('vue', 'HomeController@vue');

    Route::post('settings', 'SettingController@saveSettings');
});

Route::group([
    'middleware' => ['admin'],
], function () {
    Route::resource('users', 'Resources\UserController');
});
