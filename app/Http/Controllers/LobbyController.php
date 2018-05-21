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
    public static $radiant_bets;
    public static $dire_bets;

    public function index($game_id)
    {
        $lobby = cache($game_id);

        $players =  Lobby::getPlayers($game_id);
        $players = array_chunk($players, count($players)/2, true);
//        dd($players);
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

//        dd($users);
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
        $lobby = [];
        $lobby = cache('status_'.$game_id);

        $lobby[] += request()->place_id;
        $players = array($game_id => $lobby);

        if (count($players[$game_id]) >= 11) {
//            return redirect()->action('LobbyController@start', ['game_id' => $game_id]);
//            Cache::forget('status_'.$game_id);
//            return json_encode($players);
        } else {
            Cache::forever('status_' . $game_id, $lobby);
//             dd(cache('status_'.$game_id));

            return back();
        }
    }

    public function getIds()
    {
        $url = parse_url(url()->current());
        $game_id = explode('/', $url['path']);
        $lobby = cache('status_'.$game_id[3]);
        return $lobby;
    }

    public function start($game_id)
    {

        $lobby = cache($game_id);
        $bank = $lobby[$game_id]['bank'];
        $rank = $lobby[$game_id]['rank'];

        $players = Lobby::getPlayers($game_id); // 
        $players = array_chunk($players, count($players)/2, true);

        $radiant = $players[0];
        $dire = $players[1];


        $room = Room::find($game_id);
        if(!isset($room))
        {
            $content = 'var id = [';

            foreach ($radiant as $key => $value) {
                $content .= '[\''.$value['uid'] . '\',' . "'R'],";
            }
            foreach ($dire as $key => $value) {
                $content .= '[\''.$value['uid'] . '\',' . "'D'],";
            }
            $content .= "['$game_id']];module.exports.id = id;";
            // dd($content);
            Storage::disk('bot')->makeDirectory("bot1/games/$game_id");
            Storage::disk('bot')->put("bot1/games/$game_id/$game_id.log", $game_id);
            Storage::disk('bot')->put("bot1/players.js", $content);
            // dd($rank);
            $allRooms = cache($rank);

            $game = array_search($game_id, $allRooms);

            if($game){
                unset($allRooms[$game]);
            }

            Cache::forever($lobby[$game_id]['rank'], $allRooms);

            DB::table('rooms')->insert(
                ['id' => key($lobby),
                 'rank' => $lobby[$game_id]['rank'],
                 'bank' => $lobby[$game_id]['bank'],
                 'min_bet' => $lobby[$game_id]['min_bet'],
                 'max_bet' => $lobby[$game_id]['max_bet'],
                 'players' => $lobby[$game_id]['players'],
                ]);

            Cache::forget($game_id);
            $rootDir = $_SERVER['DOCUMENT_ROOT'];

            $bot_path = "cd "
                . "js/node-dota2/examples/bot1 "
                . "&& node start.js >> $rootDir/js/node-dota2/examples/bot1/games/$game_id/$game_id.log &";
            //. "&& node start.js >> /home/vagrant/dota2roulette/public/js/node-dota2/examples/games/$game_id/$game_id.log &";
            exec($bot_path, $out, $err);
        }
        return view('lobby.start', compact('game_id', 'radiant', 'dire','bank'));

    }

    public function res($game_id)
    {

        /*
            Полуучем результат игры из файла
            от бота по ид комнаты.
            Записываем результат в БД.
        */
        //$game_id ='20180513141046';
        $result = Lobby::checkDir($game_id);
        //$str = file_get_contents($result);
        $str = file($result, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        // $arr = explode(' ', $str);
        /*        for ($i=0; $i < count($arr); $i++) {
                    $log[current($arr)] = next($arr);
                }*/
        foreach ($str as $key => $value) {
            $arr = explode(' ', $value);
            if(current($arr) == "match_outcome"){
                $log[current($arr)] = next($arr);
                break;
            }else
                $log['match_outcome'] = 1;
        }
        //dd($log['match_outcome']);
        //dd($arr);
        DB::table('rooms')->where('id', $game_id)->update(['winners' => $log['match_outcome']]);
        //сохраняем отдельно файлик с ид пользователей
//        Storage::disk('bot')->move('players.js', 'game/'.$game_id.'players.js');
        /*
            Получаем пользователей из БД
            для расспределения выиграша
        */
        $room = Room::find($game_id);
        $winners = $room->winners;
//        $bank = $room->bank;
//        $commission = $room->bank - $bank;

        $players = json_decode($room->players, true);
        $players = array_chunk($players, count($players)/2, true);

        $radiant = $players[0];
        $dire = $players[1];

        foreach ($radiant as $key => $value) {
            self::$radiant_bets += $value['bet'];
        }

        foreach ($dire as $key => $value) {
            self::$dire_bets += $value['bet'];
        }

        if($winners == 2){
            foreach ($radiant as $key => $player) {
                if($player['uid'] != 0){
                    $award = (round($player['bet'] / self::$radiant_bets, 2)) * self::$dire_bets;
                    $userStat = Stat::find($player['uid'])?:Stat::create(['user_id' => $player['uid']]);
                    DB::table('stats')->where('user_id', $player['uid'])->
                    update(['total_games' => DB::raw('total_games + 1'), 'win_games' => DB::raw('win_games + 1'),
                            'bet_win' => DB::raw("bet_win + $award")]);
                    DB::table('users')->where('player_id', $player['uid'])->
                    update(['coins'=> (request()->user()->where('player_id', $player['uid'])->value('coins') + $player['bet'] + $award)]);
                }
            }
            foreach ($dire as $key => $player) {
                if($player['uid'] != 0){
                    $userStat = Stat::find($player['uid'])?:Stat::create(['user_id' => $player['uid']]);
                    DB::table('stats')->where('user_id', $player['uid'])->
                    update(['total_games' => DB::raw('total_games + 1'), 'lose_games' => DB::raw('lose_games + 1'),
                            'bet_lose' => DB::raw("bet_lose +" . $player['bet'])]);
                }

            }
        }
        if($winners == 3){
            foreach ($radiant as $key => $player) {
                if($player['uid'] != 0){
                    $userStat = Stat::find($player['uid'])?:Stat::create(['user_id' => $player['uid']]);
                    DB::table('stats')->where('user_id', $player['uid'])->
                    update(['total_games' => DB::raw('total_games + 1'), 'lose_games' => DB::raw('lose_games + 1'),
                            'bet_lose' => DB::raw("bet_lose +" .$player['bet'])]);
                }
            }
            foreach ($dire as $key => $player) {
                if($player['uid'] != 0){
                    $award = (round($player['bet'] / self::$dire_bets, 2)) * self::$radiant_bets;
                    $userStat = Stat::find($player['uid'])?:Stat::create(['user_id' => $player['uid']]);
                    DB::table('stats')->where('user_id', $player['uid'])->
                    update(['total_games' => DB::raw('total_games + 1'), 'win_games' => DB::raw('win_games + 1'),
                            'bet_win' => DB::raw("bet_win + $award")]);
                    DB::table('users')->where('player_id', $player['uid'])->
                    update(['coins'=> (request()->user()->where('player_id', $player['uid'])->value('coins') + ($player['bet'] + $award))]);
                }
            }
        }
        return view('lobby.result');
    }
}