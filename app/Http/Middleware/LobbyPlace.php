<?php

namespace App\Http\Middleware;

use App\Lobby;
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
        $steam_id = auth()->user()->player_id;

        if(!in_array($steam_id, Lobby::places())){
            abort('404', 'You must take some place, before you can set bets');
        }
        return $next($request);
    }
}
