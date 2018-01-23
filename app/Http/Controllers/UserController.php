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

        $playerID   = $request->user()->value('player_id');
        $allGames   = Game::get();

        return view(
            'personal.index', ['uInfo'    => Auth::user(),
                               'playerID' => $playerID, 'allGames' => $allGames]
        );
    }

    public function update(Request $request)
    {
        $playerID = $request->input('player_id');
        $service  = $request->input('service');
        $game     = $request->input('game');

        $request->user()->services()->updateOrCreate(
            ['title'    => $service,
             'games_id' => $game,
            ],
            [
            'player_id' => $playerID,
            ]
        );
        $playerID32 = Steam::toSteamID($playerID);
        $steam_data = file_get_contents(
            'https://api.opendota.com/api/players/' . $playerID32
        );
//        dd($steam_data);
        return redirect('/personal');
    }
}
