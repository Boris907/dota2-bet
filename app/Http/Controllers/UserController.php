<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Game;

class UserController extends Controller
{
		 public function __construct()
   {
    	$this->middleware('auth');

   }

     public function index(Request $request)
    {

        $playerID = $request->user()->services()->value('player_id');
        $allGames = Game::get();

        return view('personal.index', ['uInfo'=>Auth::user(),'playerID'=>$playerID,'allGames'=>$allGames]);
    }

    public function update(Request $request)
    {
        $playerID = $request->input('player_id');

        $request->user()->services()->create([
            'player_id' => $playerID,
            'title' => 'asasa',
            'games_id' => 1,
        	]);

        return redirect('/personal');
    }
}
