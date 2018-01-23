<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Game;

class Service extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $fillable
        = [
            'player_id',
            'user_id',
            'title',
            'games_id',
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
