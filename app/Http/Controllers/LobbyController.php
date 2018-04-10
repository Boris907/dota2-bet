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
use App\Bet;
use App\Room;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
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
        $players =  Lobby::getPlayers($game_id);
       /* for ($i = 1; $i <= 5; $i++) {
            $radiant[$i] = $players[$i];
        }
        for ($i = 6; $i <= 10; $i++) {
            $dire[$i] = $players[$i];
        }*/

        $players = array_chunk($players, count($players)/2, true);
        $radiant = $players[0];
        $dire = $players[1];
        $lobbies = cache($game_id);
        //dd($lobbies);
        $bank = $lobbies[$game_id]['bank'];

        return view('lobby.index', compact('game_id', 'radiant', 'dire','bank'));
    }

    public function set($game_id, $place_id)
    {
        $players = Lobby::getPlayers($game_id);
        //$players = cache('123');
        $steam_id = auth()->user()->player_id;
        $place = array_search($steam_id,array_column($players, 'uid'));
        if ($place == 0 || $place) {
            $players[$place+1]['uid'] = 0;
        }

        $players[$place_id]['uid'] = $steam_id;

        $lobby = cache($game_id);
        $lobby[$game_id]['players'] = json_encode($players);
        Cache::forever($game_id,$lobby);

        return redirect()->action('LobbyController@index', ['game_id' => $game_id]);
    }

        public function bet($game_id,$bet)
    {
        $steam_id = auth()->user()->player_id;
        $coins    = auth()->user()->coins;

        $lobbies = Cache::pull('newbie');
        $bank = $lobbies[$game_id]['bank'] + $bet;
        $lobbies[$game_id]['bank'] = $bank;
        Cache::forever('newbie',$lobbies);

        $coins -= $bet;
        request()->user()->update(['coins' => $coins]);
        //$players = Lobby::getPlayers($game_id);
        $players = Cache::pull($game_id);
        $place = array_search($steam_id,array_column($players, 'uid'));
        //$players[$place+1]['bet'] = 0;
        $players[$place+1]['bet'] += $bet;
        $bet = $players[$place+1]['bet'];
        Cache::forever($game_id,$players);
        return response()->json(["bet" => $bet, "coins" => $coins, "bank" => $bank]);

    }

//    public function get()
//    {
//        $content = 'var id = [';
//        $arrIDs = Lobby::places();
//        for ($i = 1; $i < 6; $i++) {
//            $content .= "['$arrIDs[$i]'" . ',' . "'R'],";
//        }
//        for ($i = 6; $i < 11; $i++) {
//            $content .= "['$arrIDs[$i]'" . ',' . "'D'],";
//        }
//        $content .= '];module.exports.id = id;';
//        Storage::append('public\\players.js', $content);
//        Storage::move(
//            'public\\' . Lobby::checkDir(),
//            'public\\c' . Lobby::checkDir()
//        );
//        $str = '';
//        for ($i = 1; $i <= 10; $i++) {
//            $str .= "0 $i ";
//        }
//        Storage::append(Lobby::newFile(), $str);
//
//        for ($i = 1; $i < 6; $i++) {
//            $radiant[$i] = $arrIDs[$i];
//        }
//        for ($i = 6; $i < 11; $i++) {
//            $dire[$i] = $arrIDs[$i];
//        }
//
//        //Выводит логи,
//        $bot_path = "cd "
//            . "js/node-dota2/examples"
//            . "&& node start.js >> /home/vagrant/code/auth/storage/app/public/log/dota2.log &";
////            . "&& node start.js >> /home/vagrant/code/auth/dota2-roulette/storage/app/public/log/dota2.log &";
//       // $bot_path = "ps -ef | grep node";
//        exec($bot_path, $out, $err);
//      //  dd($out);
//
//        return view('lobby.start', compact('dire', 'radiant', 'room_cash'));
//    }

    public function res()
    {
//        $lines = file('/home/vagrant/code/auth/public/js/node-dota2/examples/match.end25510595590304912');
        $lines = file('/home/vagrant/code/auth/public/js/node-dota2/examples/match1.end');
        //$fs = fopen("/home/vagrant/code/auth/public/js/node-dota2/examples/match.end25510595586138574", 'r+');
        dd($lines);
    }
}
