<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class RoomController extends Controller
{
	public function __construct()
   {
    	$this->middleware('auth');
   }

  public function index()
    {
        $id_player = Auth::user()->player_id;
        $coins = Auth::user()->coins;

        return view('rooms.index', compact(['id_player','coins']));
    }

  public function create()
    {
        return view('rooms.create', compact(['id_player']));
    }

      public function set($players)
    {
        return redirect()->action('LobbyController@index', ['min_bet'=>0])->with('players', $players);
    }

}
