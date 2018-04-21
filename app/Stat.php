<?php

namespace App;

use Illuminate\Support\Facades\DB;
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

    public static function getSteamTime()
    {
        $player_id = auth()->user()->player_id;

        $steam_time_request = file_get_contents(
            'https://steamcommunity.com/profiles/' . $player_id .'/games/?tab=all'
        );

        $steam_time_request = trim(preg_replace('/\s+/', ' ', $steam_time_request));
        $t = preg_match('#(570)#', $steam_time_request, $matches);

        if ($t != 0) {
            $steam_time = preg_match('/hours_forever":"(\d+\,\d+|\d+)/', $steam_time_request, $matches);
            $steam_time = explode('"', $matches[0]);

            DB::table('users')->where('player_id', $player_id)->update(
                ['steam_time' => $steam_time[2]]
            );
        }else{
            echo "Whoops, something get`s wrong!";
        }
    }
}
