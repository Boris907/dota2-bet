<?php

namespace App\Http\Controllers;

use App\Game;
use App\Service;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class UserController extends Controller
{
		 public function __construct()
   {
    	$this->middleware('auth');

   }

     public function index(Request $request)
    {
        $user_info = Auth::user();
        $services = Service::all();
        $games = Game::all()->where('service_id', 1);
        return view('personal.index', compact('user_info', 'services', 'games'));
    }

    public function update(Request $request)
    {
        $playerID = $request->input('player_id');

        $request->user()->update([
            'player_id' => $playerID,
            ]);

        return redirect('/personal');
    }
}
