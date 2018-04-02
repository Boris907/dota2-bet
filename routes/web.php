<?php

Route::group(['middleware' => ['web']], function () {

    Route::get('/', function () {
        return view('layouts.welcome');
    })->middleware('guest');

    Auth::routes();
        Route::get('/new_room', 'RoomController@create');
        Route::get('/new_room/set/{players}', 'RoomController@set');
    Route::group(['middleware' => ['lobby', 'auth']], function () {
        Route::get('/personal', 'UserController@index');
        Route::post('/personal', 'UserController@update');
        Route::get('/personal/rate', 'UserController@rate');

        Route::get('auth/steam', 'SteamAuthController@redirectToSteam')->name('auth.steam');
        Route::get('auth/steam/handle', 'SteamAuthController@handle')->name('auth.steam.handle');

        Route::get('logout', 'Auth\LoginController@logout')->name('logout');


        Route::get('/checkout/stripe', 'CheckoutController@getStripe');
        Route::post('/checkout/stripe', 'CheckoutController@postStripe');
        Route::get('/checkout/g2a', 'CheckoutController@getG2A');
        Route::post('/checkout/g2a', 'CheckoutController@postG2A');
        Route::get('/checkout/webmoney', 'CheckoutController@getWebMoney');

        Route::get('/stats', 'StatsController@index');
       
    });
        Route::get('/rooms', 'RoomController@index');
        Route::get('/rooms/list/{type}', 'RoomController@all');
        Route::get('/rooms/lobbi/{id}', 'RoomController@get');

        Route::get('/lobby/team/{id}', 'LobbyController@team');
        Route::get('/lobby/{rank}/start', 'LobbyController@get');
        Route::get('/lobby/{rank}/results', 'LobbyController@res');
    Route::group(['middleware' => ['bet']], function () {
        Route::get('/lobby/{rank}', 'LobbyController@index');
    });

    Route::group(['middleware' => ['place']], function () {
//        Route::post('lobby/test', 'BetsController@calculate');
        Route::post('/lobby/{bet}/set', 'BetsController@set');
        Route::get('/lobby/{rank}/reset', 'BetsController@reset');
    });
});


