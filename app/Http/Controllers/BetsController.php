<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class BetsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function set($bet)
    {
        $coins = Auth::user()->coins;
        $min_bet = request()->session()->get('min_bet');
//        if ($bet >= $min_bet) {
            $coins = $coins - $bet;
            request()->user()->update(['coins' => $coins]);
            session(['coins' => $coins, 'bet' => $bet]);
            Session::flash('flash_message', 'Your bet submited successfully');
//        }
        return redirect('/lobby/{min_bet}');
    }
}
