<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Game;
use App\Steam;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index(Request $request)
    {
        $user_info =  Auth::user();
        $player_id   = $request->user()->services()->value('player_id');
        $all_games   = Game::get();

        return view('personal.index', compact('user_info', 'player_id', 'all_games'));
    }

    public function update(Request $request)
    {
        $player_id = $request->input('player_id');
        $service  = $request->input('service');
        $game     = $request->input('game');

        $request->user()->services()->updateOrCreate(
            ['title'    => $service,
             'games_id' => $game,
            ],
            [
            'player_id' => $player_id,
            ]
        );
        $player_id32 = Steam::toSteamID($player_id);
        $steam_data = file_get_contents(
            'https://api.opendota.com/api/players/' . $player_id32
        );
//        dd($steam_data);
        return redirect('/personal');
    }
}
