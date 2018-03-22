<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    protected $fillable = ['room_rank', 'bet', 'max_bet'];

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
