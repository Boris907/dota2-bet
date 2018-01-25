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

        return view('rooms.index', compact('id_player'));
    }

}
