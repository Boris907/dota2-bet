<?php

namespace App\Http\Controllers;

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
        return view('personal.index', ['uInfo'=>Auth::user()]);
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
