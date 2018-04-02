<?php

namespace App\Http\Controllers;

use App\Room;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RoomController extends Controller
{
    public function index()
    {
        return view('rooms.index2');
    }

    public function create()
    {
        return view('rooms.create', compact(['id_player']));
    }

    public function set($players)
    {
        return redirect()->action('LobbyController@index', ['min_bet' => 0])
            ->with('players', $players);
    }

    public function all()
    {
        $allRooms = Room::checkDir();
        return view('rooms.all', ['allRooms' => $allRooms]);
    }

    public function get($id)
    {
        Cache::forever('key',$id);
        $value = cache('key');

        dd($value);
        $players = Room::show($id);
         for ($i = 1; $i <= 5; $i++) {
            $radiant[$i] = $players[$i];
        }
        for ($i = 6; $i <= 10; $i++) {
            $dire[$i] = $players[$i];
        }
        return view('rooms.get', compact(['dire', 'radiant','id']));
    }

}
