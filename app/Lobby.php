<?php

namespace App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Lobby
{
    // объявление свойства
    public static $dir = '../storage/app/public/';
    public static $fullPath;

    static public function getPlayers($game_id)
    {   
        //Cache::flush();
        if(Cache::has($game_id)){
            $lobby = cache($game_id);
            $players = $lobby[$game_id]['players'];
            $players = json_decode($players,true);
        }else{
            $lobby = DB::table('rooms')->get()->where('id', $game_id);
            $data[$lobby[0]->id] = [
                'rank' =>$lobby[0]->rank,
                'bank' =>$lobby[0]->bank,
                'min_bet' =>$lobby[0]->min_bet,
                'max_bet' =>$lobby[0]->max_bet,
                'players' =>$lobby[0]->players,
                ];
            $players = $lobby[0]->players;
            $players = json_decode($players,true);
/*            $newbie = cache('newbie');
            $players = json_decode($newbie[$game_id]['players'],true);*/
            Cache::forever($game_id,$data);
        }
        return $players;
    }

    static public function getLobby($game_id)
    {
        $lobbies = cache('newbie');
        $lobby = $lobbies[$game_id];

        return $lobby;
    }

  static public function lobbyBody()
    {
        for ($i = 1; $i <= 10; $i++) {
            $lobbies[$i] = ['type' => 0, 'bank' => 0, 'min_bet' => 0, 'max_bet' => 0, 'total' => 0];
        }
        return $lobbies;
    }

  static public function checkDir()
    {
        $files = scandir(self::$dir);
        foreach ($files as $file) {
            $check = preg_match('/^game_[0-9]+.mo/', $file);
            if ($check == 1) {
                $fileName       = $file;
                self::$fullPath = self::$dir . $fileName;

                return $fileName;
            }
        }
    }

    /*
        Генерируем индекс для названия файла
    */
    static public function newFile()
    {
        $today = date("Ymdgia");
        $gameName = "game_".$today;
        $fileName = $gameName. 'o.txt';

        return "/public/".$fileName;
    }

    static public function places()
    {
        if (self::checkDir() != null) {
            $str = file_get_contents(self::$fullPath);
            $str = str_replace("\n", "", $str);
            $arr = explode(' ', $str);
            array_pop($arr);
            for ($i = 0; $i < count($arr); $i += 2) {
                $playersID[$arr[$i + 1]] = $arr[$i];
            }

            return $playersID;
        } else {
            ($arr = 0);
        }
    }

}
	