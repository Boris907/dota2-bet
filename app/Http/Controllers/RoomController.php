<?php

namespace App\Http\Controllers;

use App\Lobby;
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
        $url_pr = url()->previous();
        $url_pr = parse_url($url_pr);
        $arr = explode('/', $url_pr['path']);
        // dd($arr);
        if(isset($arr[2]) && $arr[2] == 'lobby'){
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

        return view('rooms.index2');
    }

    public function create()
    {
        return view('rooms.create', compact(['id_player']));
    }

    public function set($chislo)
    {   
        //Cache::forget('2018041024358');
        //dd(cache('2018041024358'));
        $lobby = Room::create($chislo);
        $game_id = strval(key($lobby));

        Cache::forever($game_id,$lobby);
        return redirect()->action('LobbyController@index', ['game_id' => $game_id]);
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
