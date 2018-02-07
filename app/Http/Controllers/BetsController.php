<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $coins = Auth::user()->coins;
        $min_bet = request()->session()->get('min_bet');
        $old_bet = request()->session()->get('bet');
//        dd($bet);
        if(!isset($old_bet)) {
            $old_bet = $min_bet;
//            session(['bet' => $old_bet]);
            $coins -= $min_bet;
        }

        $coins -= $bet;
        $bet = $old_bet + $bet;
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
        session(['coins' => $coins, 'bet' => $bet]);
        Session::flash('flash_message', 'Your bet submited successfully');

        return redirect('/lobby/'.request()->session()->get('min_bet'));
    }

    public function reset($min_bet)
    {
        $coins = Auth::user()->coins;
        $old_bet = request()->session()->get('bet');
        dd($old_bet);
        $old_bet = $min_bet;


        return back();
    }
}
