<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Lobby;

class LobbyController extends Controller
{
    private $arr = array();
    private $buff;


    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index()
    {   
        /*
            Ищем свободный файл, если не находим
            создаём новый 
        */
           $id_player = Auth::user()->player_id;

        if (Lobby::checkDir() == null) {
            Storage::append(Lobby::newFile(), $id_player. ' ');

            $arrIDs = Lobby::places();
             for ($i=1; $i < 6 ; $i++) { 
                # code...
                $radiant[$i] = $arrIDs[$i];
            }
            for ($i=6; $i < 11 ; $i++) { 
                # code...
                $dire[$i] = $arrIDs[$i];
            }

            return view('lobby.index', compact(['dire','radiant']));
        }
        /*
            Если файл существует и в нём меньше 10 ид
            добавлеям ид.
            Иначе закрываем старый файл и создаём новый
            с добавлением ид.
        */
        if (file_exists(Lobby::$fullPath)) {
            $arrID = Lobby::allID();
            $arrIDs = Lobby::places();

            for ($i=1; $i < 6 ; $i++) { 
                # code...
                $radiant[$i] = $arrIDs[$i];
            }
            for ($i=6; $i < 11 ; $i++) { 
                # code...
                $dire[$i] = $arrIDs[$i];
            }
            
            if (count($arrID) - 1 == 12) {
            array_pop($arrID);
                $content = 'var id = [';
                foreach ($arrID as $value) {
                    $content .= "['$value','D'],";                
                }
                $content .= '];module.exports.id = id;';
                $content = str_replace("\n", "", $content);
                Storage::append('public\\players.js', $content);
                Storage::move(
                    'public\\' . Lobby::checkDir(),
                    'public\\c' . Lobby::checkDir()
                );
                Storage::append(Lobby::newFile(), $id_player.' ');
                //var_dump($arrID);
                return view('lobby.index', compact(['dire','radiant']));
            }
            /*
                Проверяем нет ли в файле ид пользователя
            */
            if ( ! (in_array(strval($id_player), $arrID))) {
                Storage::append(
                    'public\\' . Lobby::checkDir(), $id_player.' ');
            }
        }
        //Svar_dump($arrID);
        return view('lobby.index', compact(['dire','radiant']));
    }

    public function get()
    {
        $id_player = Auth::user()->player_id;

        $content = 'var id = [';
        $arrIDs = Lobby::places();
        for ($i=1; $i < 6 ; $i++) {
                $content .= "['$arrIDs[$i]'".','."'R'],";
            }
        for ($i=6; $i < 11 ; $i++) { 
                $content .= "['$arrIDs[$i]'".','."'D'],";
            }
        $content .= '];module.exports.id = id;';
        Storage::append('public\\players.js', $content);
 
        //Выводит логи в /dev/null,
        $bot_path = "cd " . "~/Code/Game/dota2-roulette/public/js/node-dota2/examples ". "&& node example2.js >> /tmp/dota2.log &";
        exec($bot_path, $out, $err);

        return view('lobby.start', compact('id_player'));
        //return back(); 
    }

    public function team($id)
    {
        $steam_id = Auth::user()->player_id;
        $arrIDs = Lobby::places();
        if(in_array($steam_id, $arrIDs))// если есть такой ид на его место записываем 0
        {
            $key = array_search($steam_id, $arrIDs);
            $arrIDs[$key]= 0;
    }
        $arrIDs[$id]= $steam_id; // просто добавляем ид, проверка выше исключчает повторы
        $str ='';
        foreach ($arrIDs as $key => $value) {
            $str .= $value.' '.$key.' ';
        }
        $f = fopen('/home/vagrant/code/auth/storage/app/public/'.Lobby::checkDir(), 'w+');
        $f = fwrite($f, $str);

        return back(); 
    }

}
