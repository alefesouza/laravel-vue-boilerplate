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

Auth::routes();

Route::group([
    'middleware' => ['auth']
], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    
    Route::get('/data/vue', 'HomeController@vue')->name('vue');

    Route::post('/data/settings', 'SettingController@saveSettings')->name('settings.save');
});

Route::group([
    'middleware' => ['admin'],
], function () {
    Route::resource('/data/users', 'Resources\UserController');
});

Route::get('/{any}', 'HomeController@index')->where('any', '.*')->middleware('auth');
