<?php

namespace App\Http\Controllers;

use App\Lobby;
use App\Room;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class BetsController extends Controller
{
    public $max = 10;
    public $min_bet = 4;
    public $rank;
    public $bank;
    private $bet;

    public function set($bet)
    {
        $url      = parse_url(url()->previous());
        $url      = explode('/', $url['path']);
        $steam_id = auth()->user()->player_id;
        $coins    = auth()->user()->coins;

        //Получаем массив с игроками в лобби по id комнаты
        $players = Cache::get($url[3]);
        $place   = array_search($steam_id, array_column($players, 'uid'));

        $old_bet = $players[$place + 1]['bet'];

//        if ( $old_bet == 0) {
//            $this->bet = $this->min_bet;
//            $coins   -= $this->min_bet;
//        }
        $coins                      -= $bet;
        $this->bet                  = $old_bet + $bet;
        $players[$place + 1]['bet'] = $this->bet;

        //Сумма всех ставок
        for ($i = 1; $i <= count($players); $i++) {
            $this->bank += $players[$i]['bet'];
        }

        Cache::forget($url[3]);
        Cache::forever($url[3], $players);

        $lobby = Lobby::getLobby($url[3]);
        dd(cache('newbie')->where('id', $url[3])->toArray());
        foreach ($lobby as $value) {
            $value->bank = $this->bank;
            $value->players = $players;
        }
        if (doubleval($this->bet) > $this->max) {
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
//        session(['bet' => $this->bet, 'bank' => $this->bank, 'max_bet' => $this->max]);
            DB::table('rooms')->where('id', $url[3])->update(
                ['bank' => $this->bank]
            );
            session()->put('message', 'Your bet increased successfully');

            return response()->json(
                [
                    "bank"    => $this->bank,
                    "cash"    => $coins,
                    "bet"     => $this->bet,
                    "max_bet" => $this->max
                ]
            );
        }
    }

    public function reset()
    {
        $old_bet = request()->session()->get('bet');
        $bet     = request()->session()->get('min_bet');
        $coins   = auth()->user()->coins + $old_bet - $bet;
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
