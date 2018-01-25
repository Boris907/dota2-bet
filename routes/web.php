<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
Route::group(['middleware' => ['web']], function () {

    Route::get('/', function () {
        return view('layouts.welcome');
    })->middleware('guest');

	Route::get('/personal', 'UserController@index');
    Route::post('/personal', 'UserController@update');

    Route::get('/rooms', 'RoomController@index');
    Auth::routes();

    Route::get('auth/steam', 'SteamAuthController@redirectToSteam')->name('auth.steam');
    Route::get('auth/steam/handle', 'SteamAuthController@handle')->name('auth.steam.handle');

    Route::get('logout', 'Auth\LoginController@logout')->name('logout');
	
	Route::get('/lobby', 'LobbyController@index');
	Route::get('/lobby/1', 'RoomController@get');
//	Route::get('/lobby/{id}', 'LobbyController@index')->where('id', '[0-9]+');
});

/*Route::get('user/{id}', function ($id) {
    //
})->where('id', '[0-9]+');*/


