<?php

namespace App\Http\Middleware;

use App\Lobby;
use App\Room;
use Illuminate\Support\Facades\Cache;
use Closure;

class LobbyPlace
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $url = url()->current();
        $url = parse_url($url);
        
        $url_pr = url()->previous();
        $url_pr = parse_url($url_pr);
        $arr = explode('/', $url_pr['path']);
        
        if(isset($arr[2]) && $arr == 'lobby'){
            $game_id = $arr[3];
            $players = Lobby::getPlayers($game_id); // 

            $steam_id = auth()->user()->player_id;

            $place = array_search($steam_id, array_column($players, 'uid'));
            if ($place == 0 || $place) {
                $players[$place+1]['uid'] = 0;
                $players[$place+1]['bet'] = 0;
                $players[$place+1]['mmr'] = 0;
                $players[$place+1]['rank'] = 0;
            }

            $lobby = cache($game_id);
            //dd($lobby[$game_id]['players']);
            //$lobby = cache($request->game_id);
            $lobby[$game_id]['players'] = json_encode($players);

            Cache::forever($game_id,$lobby);
    }
        return $next($request);
    }
}
