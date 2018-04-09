<?php

namespace App\Http\Controllers;

use App\Room;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RoomController extends Controller
{
    public function index()
    {
        return view('rooms.index2');
    }

    public function create()
    {
        return view('rooms.create', compact(['id_player']));
    }

    public function set($chislo)
    {
        Room::create($chislo);
        
/*        $lobby = cache('123');
        $game_id = key($lobby);
        dd($game_id);
        
        $players = json_decode($lobby[$game_id]['players'],true);
        $players = array_chunk($players, 2, true);
        
        $radiant = $players[0];
        $dire = $players[1];
        
        $bank = $lobby[$game_id]['bank'];*/

      //  return view('lobby.index', compact('game_id', 'radiant', 'dire','bank'));

    }

    public function all($rank)
    {
        //$allRooms = Room::checkDir();
        // Room::set('20180408195325','min_bet', 4);
        $lobbies = Room::lobbyList($rank);
        return view('rooms.all', ['lobbies' => $lobbies]);
    }

    /*
        Получаемвсех игроков в комнате.
        Если никого нет, создаём лобби.
    */
    public function get($game_id)
    {

        /*$players = cache($game_id) ?: Room::newLobby();
        for ($i=1; $i <= 10 ; $i++) { 
            $players[$i] = 0;
        }*/
        $players = Room::lobbyPlayers();
        
        for ($i = 1; $i <= 5; $i++) {
            $radiant[$i] = $players[$i];
        }
        for ($i = 6; $i <= 10; $i++) {
            $dire[$i] = $players[$i];
        }

        return view('rooms.get', compact(['dire', 'radiant','game_id']));
    }

    /*
        Смена места игрока в лобби
    */
    public function put($game_id,$place_id)
    {
        $players = cache($game_id) ?: Room::lobbyPlayers();
        $steam_id = auth()->user()->player_id;
        if (in_array($steam_id, $players)) {
            $key = array_search($steam_id, $players);
            $players[$key] = 0;
        }
        $players[$place_id] = $steam_id;

        Cache::forever($game_id,$players);

        return redirect()->action('LobbyController@index', ['game_id' => $game_id]);
    }

    public function start($game_id)
    {
        $content = 'var id = [';
        $players = cache($game_id);
        for ($i = 1; $i < 6; $i++) {
            $content .= "['$players[$i]'" . ',' . "'R'],";
        }
        for ($i = 6; $i < 11; $i++) {
            $content .= "['$players[$i]'" . ',' . "'D'],";
        }
        $content .= '];module.exports.id = id;';
        dd($content);

       //return redirect()->action('RoomController@get',['game_id' => $game_id]);
    }
        
}
