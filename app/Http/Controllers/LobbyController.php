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

            return view('lobby.index', compact('id_player'));
        }
        /*
            Если файл существует и в нём меньше 10 ид
            добавлеям ид.
            Иначе закрываем старый файл и создаём новый
            с добавлением ид.
        */
        if (file_exists(Lobby::$fullPath)) {
            $arrID = Lobby::allID();

            if (count($arrID) - 1 == 3) {
            array_pop($arrID);
                Storage::append('public\\players.js', 'var id = [');
                foreach ($arrID as $value) {
                    $content = '[\''. $value .'\',\'D\'],';
                    Storage::append('public\\players.js', $content);
                }
                Storage::append('public\\players.js', '];module.exports.id = id;');
                Storage::move(
                    'public\\' . Lobby::checkDir(),
                    'public\\c' . Lobby::checkDir()
                );
                Storage::append(Lobby::newFile(), $id_player.' ');
                return view('lobby.index', compact('id_player'));
            }
            /*
                Проверяем нет ли в файле ид пользователя
            */
            if ( ! (in_array(strval($id_player), $arrID))) {
                Storage::append(
                    'public\\' . Lobby::checkDir(), $id_player.' ');
            }
        }

        return view('lobby.index', compact('id_player'));
    }

    public function get()
    {
        $bot_path = "cd " . "~/Code/Game/dota2-roulette/public/js/node-dota2/examples ". "&& node example2.js 2>&1";
        exec($bot_path, $out, $err);
        return redirect('lobby.index')->with("Good luck!");
    }

}
