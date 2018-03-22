<?php

namespace App\Http\Controllers;

use App\Lobby;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $steam_id = auth()->user()->player_id;

        if(!in_array($steam_id, Lobby::places())){
            return redirect('/personal')->withErrors('Errors');
        }

        $coins   = auth()->user()->coins;
        $min_bet = request()->session()->get('min_bet');
        $url     = parse_url($_SERVER['HTTP_REFERER']);
        $rank    = explode('/', $url['path']);
        $max     = DB::table('bets')->select('max_bet')->where(
            'room_rank', $rank[2]
        )->first();
        $old_bet = request()->session()->get('bet');
        $bank = DB::table('bets')->select('bet')->where(
            'room_rank', $rank[2]
        )->first();

        if ( ! isset($old_bet)) {
            $old_bet = $min_bet;
            $coins   -= $min_bet;
        }
        $coins -= $bet;
        $bet   = $old_bet + $bet;

        if (doubleval($bet) > $max->max_bet) {
            session()->put('error', 'You can`t set bet in this room more than ' . $max->max_bet);
            abort(301);
        } else {
            /*
                Форма Change your bet:
                    - Поменять на Increase your bet
                    - Вместо поля ввода сделать label (типа 10 (первая мин ставка, после второго увеличения отображать текущую ставку)
                                                        и рядом с ним +1$, +2$, Удвоить(увеличить в 2 раза), +50% (от текущей ставки) и
                                                        принять текущюю ставку) - величина ставок чото типа такого, если что потом поменяем
                    - По нажатию Submit -> 1)минусуем полученную ставку от кошелька, обновляем запись БД
                                        -> 2)устанавливаем переключатель типа bool == true
                                            (если переключатель == true, показываем форму где вместо 10 (ну или другой мин ставки)
                                            будет значение с величинной всей ставки)
            */
            request()->user()->update(['coins' => $coins]);
            session(['coins' => $coins, 'bet' => $bet, 'rank' => $rank[2]]);

            DB::table('bets')->where('room_rank', $rank[2])->update(
                ['bet' => $bank + $bet]
            );

            session()->put('message', 'Your bet increased successfully');

            return redirect(
                '/lobby/' . request()->session()->get('min_bet')
            );
        }
    }

    public function reset()
    {
        $old_bet = request()->session()->get('bet');
        $bet     = request()->session()->get('min_bet');
        $coins   = Auth::user()->coins + $old_bet - $bet;
        request()->user()->update(['coins' => $coins]);
        session(['bet' => $bet]);

        return back();
    }

    public function calculate($array_ids)
    {
        $room_cash = DB::table('bets')->select('bet')->where(
            'room_rank', session()->get('rank')
        )->first();
        $cash      = $room_cash->bet;
        $cash      = $cash * 0.95;
        $commision = ($room_cash->bet - $room_cash->bet * 0.95);

        foreach ($array_ids as $id) {
            $coins = request()->user()->where('player_id', $id)->value('coins');
            $coins += $cash / 5;
            DB::table('users')->where('player_id', $id)->update(
                ['coins' => $coins]
            );
        }
        DB::table('bets')->where('room_rank', session()->get('rank'))->update(
            ['bet' => 0]
        );

        return back();
    }
}
