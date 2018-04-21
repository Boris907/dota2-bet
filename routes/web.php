<?php

Route::group(['middleware' => ['web']], function () {

    Route::get('/', function () {
        return view('layouts.welcome');
    })->middleware('guest');

    Auth::routes();
        
    Route::group(['middleware' => ['auth']], function () {
        //Route::group(['middleware' => ['place']], function () {
        Route::get('/new_room', 'RoomController@create');
        Route::get('/new_room/set/{chislo}', 'RoomController@set');
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
        Route::get('/rooms', 'RoomController@index');
        Route::get('/rooms/list/{rank}', 'RoomController@all');
   // });
    Route::group(['middleware' => ['bet']], function () {
        Route::get('/rooms/lobby/exit', 'RoomController@index');
        Route::get('/rooms/lobby/{game_id}', 'LobbyController@index');
    });
        Route::get('/rooms/lobby/{game_id}/place/{place_id}', 'LobbyController@set');
        Route::post('/rooms/lobby/{game_id}/bet/{bet}', 'LobbyController@bet');
        Route::get('/rooms/lobby/{game_id}/start', 'RoomController@start');
    Route::group(['middleware' => ['lobby']], function () { 
    });
    
    });
//        Route::get('/lobby/{rank}', 'LobbyController@index');

/*    Route::group(['middleware' => ['place']], function () {
//        Route::post('lobby/test', 'BetsController@calculate');
        Route::post('/lobby/{bet}/set', 'BetsController@set');
        Route::get('/lobby/{rank}/reset', 'BetsController@reset');
    });*/
});


