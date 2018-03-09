<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use phpQuery;
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

        $user_info  = Auth::user();
        $user_stats = Stat::all()->where('user_id', Auth::user()->id)->toArray(
        );

        $recent_games = file_get_contents(
            "https://api.opendota.com/api/players/$player_id32/recentMatches"
        );
        $games        = json_decode($recent_games, 1);


        return view(
            'personal.stats', compact('user_info', 'user_stats', 'games')
        );
    }

    public function getSteamTime()
    {
        $player_id = Auth::user()->player_id;

        $steam_time_request = file_get_contents(
            'http://steamcommunity.com/profiles/' . $player_id
        );
        $pq  = phpQuery::newDocument($steam_time_request);
        $res = preg_match('#(Dota 2)#', pq('.game_name > a')->text());

        if ($res != 0) {
            $steam_time = $pq->find('.game_name:first')->prev()->text();
            $steam_time = trim(preg_replace('/\s+/', ' ', $steam_time));
            $steam_time = explode(' ', $steam_time);

            DB::table('users')->where('player_id', $player_id)->update(
                ['steam_time' => $steam_time[0]]
            );
        }
    }
}
