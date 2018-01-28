<?php

namespace App\Http\Controllers;

use App\Stat;
use App\User;
use App\Steam;

use Illuminate\Http\Request;
use Auth;

class StatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $player_id   = Auth::user()->player_id;
        $player_id32 = Steam::toSteamID($player_id);

        $user_info = Auth::user();
        $user_stats = Stat::all()->where('user_id', Auth::user()->id)->toArray();

        $recent_games = file_get_contents("https://api.opendota.com/api/players/$player_id32/recentMatches");
        $games = json_decode($recent_games, 1);

        dd(Steam::win($games[0]['match_id']));

        return view('personal.stats', compact('user_info','user_stats', 'games'));
    }

}
