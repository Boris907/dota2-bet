<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    protected $fillable = [
      'room_rank',
      'bet'
    ];
}
