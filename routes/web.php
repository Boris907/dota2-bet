<?php
use Illuminate\Support\Facades\Session;
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
    Route::get('/personal/rate', 'UserController@rate');

	Route::get('/rooms', 'RoomController@index');
    Auth::routes();

    Route::get('auth/steam', 'SteamAuthController@redirectToSteam')->name('auth.steam');
    Route::get('auth/steam/handle', 'SteamAuthController@handle')->name('auth.steam.handle');

    Route::get('logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('lobby/test', 'BetsController@calculate');
    Route::get('/lobby/{min_bet}', 'LobbyController@index');
    Route::get('/lobby/start', 'LobbyController@get');
    Route::get('/lobby/team/{id}', 'LobbyController@team');
    Route::post('/lobby/{bet}/set', 'BetsController@set');
    Route::get('/lobby/{min_bet}/reset', 'BetsController@reset');

    Route::get('/checkout/stripe', 'CheckoutController@getStripe');
    Route::post('/checkout/stripe', 'CheckoutController@postStripe');

    Route::get('/checkout/g2a', 'CheckoutController@getG2A');
    Route::post('/checkout/g2a', 'CheckoutController@postG2A');

    Route::get('/checkout/webmoney', 'CheckoutController@getWebMoney');


    Route::get('/stats', 'StatsController@index');
});

/*Route::get('user/{id}', function ($id) {
    //
})->where('id', '[0-9]+');*/


