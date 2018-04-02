<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
    public static $dir = '../storage/app/public/';
    public static $fullPath;
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
        Создание комнаты/игры
    */
    static public function newFile()
    {
        /*
            Исключить повторения
        */
        $today = date("YmdGis");   
        $fileName = $today. '_o';
        $str = '';
        for ($i = 1; $i <= 10; $i++) {
             $str .= '0 ' . $i . ' ';
        }

        Storage::append('/public/'.$fileName, $str);

    }

        static public function show($game)
    {
        $str = file_get_contents(self::$dir.$game);
        $str = str_replace("\n", "", $str);
        $arr = explode(' ', $str);
        array_pop($arr);
        for ($i = 0; $i < count($arr); $i += 2) {
            $playersID[$arr[$i + 1]] = $arr[$i];
        }

        return $playersID;
    }

}
