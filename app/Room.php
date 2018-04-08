<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Room extends Model
{
    //protected $fillable = ['room_rank', 'min_bet', 'max_bet', 'bet', 'url'];
/*
    public static function desc()
    {
        return static::selectRaw('*')
            ->orderBy('min_bet','desc')
            ->get()
            ->toArray();
    }

    public static function asc()
    {
        return static::selectRaw('*')
            ->orderBy('min_bet','asc')
            ->get()
            ->toArray();
    }

    public static function unsetBet()
    {
        $min_bet = request()->session()->get('min_bet');
        $bet = request()->session()->pull('bet');
        $user_coins = auth()->user()->coins;
        $coins = $user_coins + $bet - $min_bet;

        request()->user()->update([
            'coins' =>$coins
        ]);
    }*/
    public static $dir ='../storage/app/public/';
    public static $fullPath;
    protected $fillable = ['id','rank', 'bank', 'min_bet', 'max_bet', 'players'];
    /*
        Ищем открытые комнаты
    */
    static public function checkDir()
    {
        $files = array_diff(scandir(self::$dir), array('..', '.'));
        $files = array_values($files);
        $list = preg_grep('/^[0-9]+_o/', $files);
        
        return $list;
    }

    /*
        Создание игры
    */
    static public function lobbyPlayers()
    {
        /*
            Исключить повторения
        */
        for ($i = 1; $i <= 10; $i++) {
            $players[$i] = ['uid' => 0, 'bet' => 0, 'mmr' => 0, 'rank' => 0];
        }

        /*Storage::append('/public/'.$fileName, $str);*/
        return $players;
    }

    /*
        Получаем все свободные лобби по rank,
        из кэша, если их там нет берём из БД
        и записываем в кэш
    */
    static public function lobbyList($rank)
    {
//        $today = date("YmdGis");
//        //$fileName = $today. '_o';
//        $players = json_encode(self::lobbyPlayers());
//        /*        $players = json_decode($players);*/
//        for ($i = 1; $i <= 10; $i++) {
//            $lobbies[$today+$i] = ['rank' => $rank, 'bank' => 0, 'min_bet' => 0, 'max_bet' => 0, 'players' => $players];
//            DB::table('rooms')->insert(
//                ['id' =>$today+$i,
//                 'rank' =>$lobbies[$today+$i]['rank'],
//                 'bank' =>$lobbies[$today+$i]['bank'],
//                 'min_bet' =>$lobbies[$today+$i]['min_bet'],
//                 'max_bet' =>$lobbies[$today+$i]['max_bet'],
//                 'players' =>$lobbies[$today+$i]['players'],
//                ]);
//        }
//        $lobbies = cache($rank);/*?: DB::table('rooms')->get();*/
        $lobbies = DB::table('rooms')->get();
        Cache::forever($rank, $lobbies);

        return $lobbies;
    }

        static public function show($game)
    {
       if (self::checkDir() != null) {
            $str = file_get_contents(self::$dir.$game);
            $str = str_replace("\n", "", $str);
            $arr = explode(' ', $str);
            array_pop($arr);
            for ($i = 0; $i < count($arr); $i += 2) {
                $playersID[$arr[$i + 1]] = $arr[$i];
            }

            return $arr;
        } else {
            ($arr = 0);
        }
    }

}
