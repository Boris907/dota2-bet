<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['room_rank', 'min_bet', 'max_bet', 'bet', 'url'];

    public static function desc()
    {
        return static::selectRaw('*')
            ->orderBy('min_bet','desc')
            ->get()
            ->toArray();
    }

    public static function asc()
    {
        return static::selectRaw('*')
            ->orderBy('min_bet','asc')
            ->get()
            ->toArray();
    }

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
