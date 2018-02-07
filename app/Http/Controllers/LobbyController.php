<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Lobby;

class LobbyController extends Controller
{
    private $arr = array();

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index($min_bet)
    {
        switch ($min_bet) {
    case 'newbie':
        $min_bet = 2;
        break;
    case 'ordinary':
        $min_bet = 4;
        break;
    case 'expert':
        $min_bet = 10;
        break;
}
        session(['min_bet' => $min_bet]);
        $arrIDs = Lobby::places();
        //Генерим новый файл, если их нет и записываем ид
        if (empty($arrIDs)) {
            $str = '';
            for ($i = 1; $i < 11; $i++) {
                $str .= '0 ' . $i . ' ';
            }
            Storage::append(Lobby::newFile(), $str);
        }
        for ($i = 1; $i < 6; $i++) {
            $radiant[$i] = $arrIDs[$i];
        }
        for ($i = 6; $i < 11; $i++) {
            $dire[$i] = $arrIDs[$i];
        }

        return view(
            'lobby.index', compact(['dire', 'radiant', 'coins'])
        );
    }


    public function get()
    {
        $content = 'var id = [';
        $arrIDs = Lobby::places();
        for ($i = 1; $i < 6; $i++) {
            $content .= "['$arrIDs[$i]'" . ',' . "'R'],";
        }
        for ($i = 6; $i < 11; $i++) {
            $content .= "['$arrIDs[$i]'" . ',' . "'D'],";
        }
        $content .= '];module.exports.id = id;';
        Storage::append('public\\players.js', $content);
        Storage::move(
            'public\\' . Lobby::checkDir(),
            'public\\c' . Lobby::checkDir()
        );
        $str = '';
        for ($i = 1; $i <= 10; $i++) {
            $str .= "0 $i ";
        }
        Storage::append(Lobby::newFile(), $str);


       //Выводит логи в /dev/null,
       $bot_path = "cd " . "~/Code/Game/dota2-roulette/public/js/node-dota2/examples ". "&& node example2.js >> /tmp/dota2.log &";
       exec($bot_path, $out, $err);

//               return view('lobby.start', compact('id_player'));

        return back();
    }

    public function team($id)
    {
        $steam_id = Auth::user()->player_id;
        $arrIDs = Lobby::places();
        if (in_array(
            $steam_id, $arrIDs
        )
        )// если есть такой ид на его место записываем 0
        {
            $key = array_search($steam_id, $arrIDs);
            $arrIDs[$key] = 0;
        }
        $arrIDs[$id]
            = $steam_id; // просто добавляем ид, проверка выше исключчает повторы
        $str = '';
        foreach ($arrIDs as $key => $value) {
            $str .= $value . ' ' . $key . ' ';
        }
        $f = fopen(
            Lobby::$dir
            . Lobby::checkDir(), 'w+'
        );
        $f = fwrite($f, $str);

        return back();
    }

}
