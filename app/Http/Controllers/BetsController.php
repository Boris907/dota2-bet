<?php

namespace App\Http\Controllers;

use App\Lobby;
use App\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class BetsController extends Controller
{
    public $max;
    public $rank;
    public $bank;

    public function set($bet)
    {
        $coins = auth()->user()->coins;
        $min_bet = request()->session()->get('min_bet');
        $url = parse_url($_SERVER['HTTP_REFERER']);
        $room = Room::all()->where('url', $url['path'])->toArray();

        foreach ($room as $item){
            $this->max = $item['max_bet'];
            $this->rank = $item['room_rank'];
            $this->bank = $item['bet'];
        }
        $old_bet = request()->session()->get('bet');

        if ( ! isset($old_bet)) {
            $old_bet = $min_bet;
            $coins   -= $min_bet;
        }
        $coins -= $bet;
        $bet   = $old_bet + $bet;

        if (doubleval($bet) > $this->max) {
            session()->put(
                'error',
                'You can`t set bet in this room more than ' . $this->max
            );
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
            session(['coins' => $coins, 'bet' => $bet, 'rank' => $this->rank, 'url' => $url['path']]);

            DB::table('rooms')->where('url', $url['path'])->update(
                ['bet' => $this->bank + $bet]
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
        $cash      = $this->bank * 0.95;
        $commision = ($this->bank - $cash);

        foreach ($array_ids as $id) {
            $coins = request()->user()->where('player_id', $id)->value('coins');
            $coins += $cash / 5;
            DB::table('users')->where('player_id', $id)->update(
                ['coins' => $coins]
            );
        }
        DB::table('rooms')->where('url', session()->get('url'))->update(
            ['bet' => 0]
        );

        return back();
    }
}
