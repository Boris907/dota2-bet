<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
           
        if (Lobby::checkDir() == null) {
            Storage::append(Lobby::newFile(), Auth::user()->player_id. ' ');

            return view('lobby.index', ['uId' => Auth::user()->player_id]);
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
                Storage::append(Lobby::newFile(),Auth::user()->player_id.' ');

                return view('lobby.index', ['uId' => Auth::user()->player_id]);
            }
            /*
                Проверяем нет ли в файле ид пользователя
            */
            if ( ! (in_array(strval(Auth::user()->player_id), $arrID))) {
                Storage::append(
                    'public\\' . Lobby::checkDir(), Auth::user()->player_id.' ');
            }
        }

        return view('lobby.index', ['uId' => Auth::user()->player_id]);
    }

}
