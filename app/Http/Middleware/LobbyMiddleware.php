<?php

namespace App\Http\Middleware;

use App\Bet;
use App\Lobby;
use Closure;

class LobbyMiddleware
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
        $steam_id = auth()->user()->player_id;
        $arr_ids = Lobby::places();
        $search = in_array($steam_id, $arr_ids);

        if ($search == true) {
            $key = array_search($steam_id, $arr_ids);
            $arr_ids[$key] = 0;
            $str = '';
            foreach ($arr_ids as $key => $value) {
                $str .= $value . ' ' . $key . ' ';
            }

            $f = fopen(Lobby::$dir . Lobby::checkDir(), 'w+');
            $f = fwrite($f, $str);

            Bet::unsetBet();
        }

        return $next($request);
    }
}
