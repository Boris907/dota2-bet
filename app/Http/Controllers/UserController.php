<?php

namespace App\Http\Controllers;

use App\Game;
use App\Service;
use App\Steam;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index()
    {
        $user_info = Auth::user();
        $services  = Service::all();
        $games     = Game::all()->where('service_id', 1);

        return view(
            'personal.index', compact('user_info', 'services', 'games')
        );
    }

    public function update()
    {
        $player_id = request()->input('player_id');

        request()->user()->update(['player_id' => $player_id,]);

        return redirect('/personal');
    }

    public function rate()
    {
        $player_id   = Auth::user()->player_id;
        $player_id32 = Steam::toSteamID($player_id);

        $steam_data = file_get_contents(
            'https://api.opendota.com/api/players/' . $player_id32
        );
        $arr = json_decode($steam_data, 1);
        if (empty($arr['solo_competitive_rank'])){
            request()->user()->update(
            ['rate' => $arr['mmr_estimate']['estimate']]
        );
        }else{
        request()->user()->update(
            ['rate' => $arr['solo_competitive_rank']]
        );
        }
        return redirect('/personal');
    }
}
