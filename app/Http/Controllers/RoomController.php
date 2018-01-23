<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoomController extends Controller
{
	public function __construct()
   {
    	$this->middleware('auth');
   }

    public function index()
    {
        $player_id = request()->user()->services()->value('player_id');
        return view('rooms.index', compact('player_id'));
    }

}
