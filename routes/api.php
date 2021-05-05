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
], function () {
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout');

    Route::post('register', 'RegisterController@register');
});

Route::group([
    'middleware' => ['auth:sanctum'],
], function () {
    Route::get('vue', 'HomeController@vue');
    Route::post('user', 'HomeController@user');

    Route::post('settings', 'SettingController@saveSettings');
});

Route::group([
    'middleware' => ['admin'],
], function () {
    Route::resource('users', 'Resources\UserController', [
        'except' => ['create', 'edit', 'show'],
    ]);
});

Route::any('messages/{type}/{id}', function ($type, $id) {
    $data = array(
       'text' => rand(),
       'message' => 'When you reload this page, it will send a broadcast notification via Pusher, adding a random number on the other tab.',
    );

    if ($type === 'private') {
        $user = \App\User::findOrFail($id);
        $user->notify(new \App\Notifications\PrivateMessageNotification($data));
    } else {
        event(new \App\Events\PublicMessagePusherEvent($data));
    }

    return response()->json($data);
});
