<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    public static function unsetBet()
    {
        $min_bet = request()->session()->get('min_bet');
        $bet = request()->session()->pull('bet');
        $user_coins = auth()->user()->coins;
        $coins = $user_coins + $bet - $min_bet;

        request()->user()->update([
            'coins' =>$coins
        ]);
    }
}
