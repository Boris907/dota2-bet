<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    protected $fillable = [
        'user_id',
        'game_id',
        'total_games',
        'win_games',
        'lose_games',
        'bet_lose',
        'bet_win'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
