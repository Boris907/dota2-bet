<?php

namespace App\Http\Controllers;

use App\Room;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index()
    {
        return view('rooms.index');
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

}
