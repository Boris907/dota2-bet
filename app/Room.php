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

}
