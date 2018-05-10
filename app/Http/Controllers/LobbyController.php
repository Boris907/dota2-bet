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

use App\Stat;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Lobby;
use App\Room;

class LobbyController extends Controller
{

    public $max;
    public $min_bet;
    public $rank;
    public $bank;

    public function index($game_id)
    {
        $lobby = cache($game_id);
        
        $players =  Lobby::getPlayers($game_id);
        $players = array_chunk($players, count($players)/2, true);
        // $users = User::all('player_id')->toArray();
        
        // for ($i=1; $i < 11; $i++) { 
        //     $players[$i]['uid'] = $users[$i-1]['player_id'];
        // }
        // $lobby[$game_id]['players'] = json_encode($players);
        // Cache::forever($game_id,$lobby[$game_id]);
        // dd(cache($game_id));
        $radiant = $players[0];
        $dire = $players[1];
        $lobby = cache($game_id);
        $bank = $lobby[$game_id]['bank'];
        return view('lobby.index', compact('game_id', 'radiant', 'dire', 'bank'));
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

        public function bet($game_id, $bet)
    {
        $steam_id = auth()->user()->player_id;
        $players = Lobby::getPlayers($game_id);
        $coins    = auth()->user()->coins;
        $lobby = cache($game_id);
        $place = array_search($steam_id, array_column($players, 'uid'));

        if ($place === 0 || $place) {
        $bank = $lobby[$game_id]['bank'] + $bet;
        $lobby[$game_id]['bank'] = $bank;
        
        $coins -= $bet;
        request()->user()->update(['coins' => $coins]);
        
        $players[$place+1]['bet'] += $bet;
        $bet = $players[$place+1]['bet'];

        $lobby[$game_id]['players'] = json_encode($players);
        Cache::forever($game_id, $lobby);
        return response()->json(["bet" => $bet, "coins" => $coins, "bank" => $bank]);
    } else {
        return response()->json(['error' => 'Choose your team first'], 500); // Status code here
        }
    }

    public function leave()
    {
        $url_pr = url()->previous();
        $url_pr = parse_url($url_pr);
        $arr = explode('/', $url_pr['path']);

        if(isset($arr[2]) && $arr[2] == 'lobby'){
            $game_id = $arr[3];
            $players = Lobby::getPlayers($game_id); // 
            $lobby = cache($game_id);

            $steam_id = auth()->user()->player_id;
            $coins = auth()->user()->coins;

            $place = array_search($steam_id, array_column($players, 'uid'));
            if ($place == 0 || $place) {
                $lobby[$game_id]['bank'] = $lobby[$game_id]['bank'] - ($players[$place+1]['bet'] - $lobby[$game_id]['min_bet']);
                request()->user()->update(['coins' => $coins + ($players[$place+1]['bet'] - $lobby[$game_id]['min_bet'])]);
                $players[$place+1]['uid'] = 0;
                $players[$place+1]['bet'] = 0;
                $players[$place+1]['mmr'] = 0;
                $players[$place+1]['rank'] = 0;
            }
            $lobby[$game_id]['players'] = json_encode($players);

            Cache::forever($game_id,$lobby);
        }
        return redirect()->action('RoomController@index');
    }

    public function all($game_id){
        $lobby = cache($game_id);
        $users = User::all('player_id')->toArray();
        $players = Lobby::getPlayers($game_id);
        for ($i=1; $i < 11; $i++) { 
            $players[$i]['uid'] = $users[$i-1]['player_id'];
            $players[$i]['bet'] = 2;
        }
        $lobby[$game_id]['players'] = json_encode($players);
        $lobby[$game_id]['bank'] = 20;
        Cache::forever($game_id,$lobby);
        return back();
        
    }
//    public function leave()
//    {
//        $url_pr = url()->previous();
//        $url_pr = parse_url($url_pr);
//        $arr = explode('/', $url_pr['path']);
//        $game_id = $arr[3];
//        $players = Lobby::getPlayers($game_id); //
//        $lobby = cache($game_id);
//        $steam_id = auth()->user()->player_id;
////        $coins = auth()->user()->coins;
//
//        $place = array_search($steam_id, array_column($players, 'uid'));
//
//        if ($place == 0 || $place) {
//            $players[$place+1]['uid'] = 0;
//            $players[$place+1]['bet'] = 0;
//            $players[$place+1]['mmr'] = 0;
//            $players[$place+1]['rank'] = 0;
//        }
//
//        $lobby[$game_id]['players'] = json_encode($players);
//
//        Cache::forever($game_id,$lobby);
//
//        return back();
//    }

    public function setId($game_id)
    {
//        Cache::forget('status_'.$game_id);

        $lobby = cache('status_'.$game_id);
        $lobby[] += request()->place_id;
        $players = array($game_id => $lobby);
        if (count($players[$game_id]) >= 1) {
            // dd(cache('status_'.$game_id));
            Cache::forget('status_'.$game_id);
            return redirect()->action('LobbyController@start', ['game_id' => $game_id]);
        } else {
            Cache::forever('status_' . $game_id, $lobby);
            // dd(cache('status_'.$game_id));

            return back();
        }
    }

    public function start($game_id)
    {
        $lobby = cache($game_id);

        /*$room = DB::table('rooms')
                     ->select('id')
                     ->where('id', 5)
                     ->get();*/
        $room = Room::find($game_id);             
        if(!isset($room)){        
        DB::table('rooms')->insert(
            ['id' => key($lobby),
            'rank' => $lobby[$game_id]['rank'],
            'bank' => $lobby[$game_id]['bank'],
            'min_bet' => $lobby[$game_id]['min_bet'],
            'max_bet' => $lobby[$game_id]['max_bet'],
            'players' => $lobby[$game_id]['players'],
        ]);
        }
        $content = 'var id = [';
        $players = Lobby::getPlayers($game_id); // 
        $players = array_chunk($players, count($players)/2, true);

        $bank = $lobby[$game_id]['bank'];

        $radiant = $players[0];
        foreach ($radiant as $key => $value) {
            $content .= '[\''.$value['uid'] . '\',' . "'R'],";
        }
        $dire = $players[1];
        foreach ($dire as $key => $value) {
              $content .= '[\''.$value['uid'] . '\',' . "'D'],";
        }
        $content .= "['$game_id']];module.exports.id = id;";
        // dd($content);
        Storage::disk('bot')->put('players.js', $content);
        //dd($content);
        $allRooms = cache($lobby[$game_id]['rank']);
        $game = array_search($game_id, $allRooms);

         unset($allRooms[$game]);

         Cache::forever($lobby[$game_id]['rank'], $allRooms);
         Cache::forget($game_id);

        /*$bot_path = "cd "
           . "js/node-dota2/examples"
           // . "&& node start.js >> /home/vagrant/code/auth/storage/app/public/log/dota2.log &";
           . "&& node start.js >> /home/vagrant/dota2roulette/storage/app/public/log/dota2.log &";
        // $bot_path = "ps -ef | grep node";
        // dd($bot_path);
       exec($bot_path, $out, $err);*/

        return view('lobby.start', compact('game_id', 'radiant', 'dire','bank'));
    }

    public function res($game_id)
    {
        
        /*
            Полуучем результат игры из файла
            от бота по ид комнаты.
            Записываем результат в БД.
        */
        $result = Lobby::checkDir($game_id);
        $str = file_get_contents($result);
        $arr = explode(' ', $str);
        //dd($arr);
        DB::table('rooms')->where('id', $game_id)->update(['winners' => $arr[2]]);
        //сохраняем отдельно файлик с ид пользователей
        Storage::disk('bot')->move('players.js', 'game/'.$game_id.'players.js');
        /*
            Получаем пользователей из БД
            для расспределения выиграша
        */
        $room = Room::find($game_id);
        $winners = $room->winners;
        $bank = $room->bank;
        $userCash = $bank/5;
        $players = json_decode($room->players, true);
        $players = array_chunk($players, count($players)/2, true);

        $radiant = $players[0];
        $dire = $players[1];
       // dd($radiant,$dire);

        if($winners == 2){
            foreach ($radiant as $key => $player) {
                if($player['uid'] != 0){
                    $userStat = Stat::find($player['uid'])?:Stat::create(['user_id' => $player['uid']]);
                    DB::table('stats')->where('user_id', $player['uid'])->
                        update(['total_games' => DB::raw('total_games + 1'), 'win_games' => DB::raw('win_games + 1'),
                       'bet_win' => DB::raw("bet_win + $userCash")]);
                    DB::table('users')->where('player_id', $player['uid'])->
                    update(['coins'=> (request()->user()->where('player_id', $player['uid'])->value('coins') + $userCash)]);
                }
            }
            foreach ($dire as $key => $player) {
                if($player['uid'] != 0){
                    $userStat = Stat::find($player['uid'])?:Stat::create(['user_id' => $player['uid']]);
                    DB::table('stats')->where('user_id', $player['uid'])->
                    update(['total_games' => DB::raw('total_games + 1'), 'lose_games' => DB::raw('lose_games + 1'),
                        'bet_lose' => DB::raw("bet_lose + $userCash")]);
                }

            }
        }
        if($winners == 3){
            foreach ($radiant as $key => $player) {
                if($player['uid'] != 0){
                    $userStat = Stat::find($player['uid'])?:Stat::create(['user_id' => $player['uid']]);
                DB::table('stats')->where('user_id', $player['uid'])->
                    update(['total_games' => DB::raw('total_games + 1'), 'lose_games' => DB::raw('lose_games + 1'),
                        'bet_lose' => DB::raw("bet_lose + $userCash")]);
                }
            }
            foreach ($dire as $key => $player) {
                if($player['uid'] != 0){
                    $userStat = Stat::find($player['uid'])?:Stat::create(['user_id' => $player['uid']]);
                    DB::table('stats')->where('user_id', $player['uid'])->
                        update(['total_games' => DB::raw('total_games + 1'), 'win_games' => DB::raw('win_games + 1'),
                       'bet_win' => DB::raw("bet_win + $userCash")]);
                    DB::table('users')->where('player_id', $player['uid'])->
                    update(['coins'=> (request()->user()->where('player_id', $player['uid'])->value('coins') + $userCash)]);
                }
            }
        }
        //$lines = file('/home/vagrant/dota2roulette/public/js/node-dota2/examples/'.$game_id.'end');
        //$fs = fopen("/home/vagrant/code/auth/public/js/node-dota2/examples/match.end25510595586138574", 'r+');
    }
}
