<?php

namespace App\Http\Controllers;

    /*
        Индексный метод - будет как метод создания лобби
            без проверок (ну или назову его креате)

        Все проверки вынести в отдельный метод шоув
            (ну или индекс если индекс будет креате)

        Если чувак создаёт лобби он выбирает колво игроков (100% кратное 2)
        Ставки хз

        Режимы кастомные
        5х5, 1x1 я думаю этого хватит

        Все лобби пробуем в БД вместо файликов
            -лобби ид
             или даер и радиант - я думаю лучше
             или 10 чуваков - тоже норм
             статус лобби - идёт игра/набор/конец
    */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Lobby;

class LobbyController extends Controller
{

    public $max;
    public $min_bet;
    public $rank;
    public $bank;

    public function index($game_id)
    {
        $lobby = cache($game_id);
        //dd($lobby);
        $players =  Lobby::getPlayers($game_id);
        $players = array_chunk($players, count($players)/2, true);

        $radiant = $players[0];
        $dire = $players[1];
        $lobby = cache($game_id);
        //dd($lobby);
        $bank = $lobby[$game_id]['bank'];
        //$bank = $lobby[0]->bank;
        return view('lobby.index', compact('game_id', 'radiant', 'dire','bank'));
    }

    public function set($game_id, $place_id)
    {
        $players = Lobby::getPlayers($game_id);
        $steam_id = auth()->user()->player_id;

        $place = array_search($steam_id,array_column($players, 'uid'));
        if ($place === 0 || $place) {
            return redirect()->action('LobbyController@index', ['game_id' => $game_id]);
        }

        $players[$place_id]['uid'] = $steam_id;

        $lobby = cache($game_id);
        $lobby[$game_id]['players'] = json_encode($players);
        
        $coins    = auth()->user()->coins;
        $lobby[$game_id]['bank'] += $lobby[$game_id]['min_bet'];
        
        $coins -= $lobby[$game_id]['min_bet'];
        request()->user()->update(['coins' => $coins]);

        $players[$place_id]['bet'] += $lobby[$game_id]['min_bet'];
        $lobby[$game_id]['players'] = json_encode($players);
        Cache::forever($game_id,$lobby);

        return redirect()->action('LobbyController@index', ['game_id' => $game_id]);
    }

        public function bet($game_id,$bet)
    {
        $steam_id = auth()->user()->player_id;
        $players = Lobby::getPlayers($game_id);
        $coins    = auth()->user()->coins;
        $lobby = cache($game_id);
        $place = array_search($steam_id, array_column($players, 'uid'));

        if ($place === 0 || $place) {
        //$players = Lobby::getPlayers($game_id);
        //$players[$place+1]['bet'] = 0;
        $bank = $lobby[$game_id]['bank'] + $bet;
        $lobby[$game_id]['bank'] = $bank;
        
        $coins -= $bet;
        request()->user()->update(['coins' => $coins]);
        
        $players[$place+1]['bet'] += $bet;
        $bet = $players[$place+1]['bet'];

        $lobby[$game_id]['players'] = json_encode($players);
        Cache::forever($game_id,$lobby);
        return response()->json(["bet" => $bet, "coins" => $coins, "bank" => $bank]);
    }else{
        //return redirect()->action('LobbyController@index', ['game_id' => $game_id])->withErrors(['msg', 'The Message']);
        return response()->json(['error' => 'Choose your team first'], 500); // Status code here
        //return redirect()->action('LobbyController@index', ['game_id' => $game_id]);
        //return redirect()->action('LobbyController@index')->withErrors(['msg', 'The Message']);
        }
    }

    public function exit()
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
            $lobby[$game_id]['bank'] = 0;
            $lobby[$game_id]['players'] = json_encode($players);

            Cache::forever($game_id,$lobby);
        }
        return redirect()->action('RoomController@index');
    }
        public function leave()
    {
        $url_pr = url()->previous();
        $url_pr = parse_url($url_pr);
        $arr = explode('/', $url_pr['path']);
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
        $lobby[$game_id]['bank'] = 0;
        $lobby[$game_id]['players'] = json_encode($players);

        Cache::forever($game_id,$lobby);

        return back();
    }

    public function start($game_id)
    {
        $lobby = cache($game_id);

        DB::table('rooms')->insert(
            ['id' => key($lobby),
            'rank' => $lobby[$game_id]['rank'],
            'bank' => $lobby[$game_id]['bank'],
            'min_bet' => $lobby[$game_id]['min_bet'],
            'max_bet' => $lobby[$game_id]['max_bet'],
            'players' => $lobby[$game_id]['players'],
        ]);
        
        $content = 'var id = [';
        $players = Lobby::getPlayers($game_id); // 
        $players = array_chunk($players, count($players)/2, true);

        $bank = $lobby[$game_id]['bank'];

        $radiant = $players[0];
        foreach ($radiant as $key => $value) {
            //$content .= "['$value['uid']'".","."'R'],";
            $content .= '[\''.$value['uid'] . ',' . "'R'],";
        }
        $dire = $players[1];
        foreach ($dire as $key => $value) {
/*            $content .= $value['uid'];*/
              $content .= '[\''.$value['uid'] . ',' . "'D'],";
        }
        $content .= '];module.expots.id = id;';

        $allRooms = cache($lobby[$game_id]['rank']);
        $game = array_search($game_id, $allRooms);
        unset($allRooms[$game]);
        Cache::forever($lobby[$game_id]['rank'], $allRooms);
        Cache::forget($game_id);
        return view('lobby.start', compact('game_id', 'radiant', 'dire','bank'));

       //return redirect()->action('RoomController@get',['game_id' => $game_id]);
    }

    public function res()
    {
//        $lines = file('/home/vagrant/code/auth/public/js/node-dota2/examples/match.end25510595590304912');
        $lines = file('/home/vagrant/code/auth/public/js/node-dota2/examples/match1.end');
        //$fs = fopen("/home/vagrant/code/auth/public/js/node-dota2/examples/match.end25510595586138574", 'r+');
        dd($lines);
    }
}
