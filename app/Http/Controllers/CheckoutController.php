<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use League\Flysystem\Exception;
use Stripe\Charge;
use Stripe\Stripe;


class CheckoutController extends Controller
{
    public function getStripe()
    {
        return view('checkout.stripe');
    }


    public function postStripe()
    {
        $user_coins = Auth::user()->coins;

        Stripe::setApiKey("sk_test_gsnmhNoQKUSOkQOTH5pAE7yZ");
        try{
            $charge = Charge::create([
                "amount" => request()->input('refill') * 100,
                "currency" => "usd",
                "source" => request()->input('stripeToken'), // obtained with Stripe.js
                "description" => "Test Charge"
            ]);
            if ($charge){
                request()->user()->update([
                    'coins' => $user_coins + request()->input('refill')
                ]);
            }
        }catch (Exception $e){

            return redirect('/personal')->with('error', $e->getMessage());
        }

        return redirect('/personal')->with('success', 'Successfully purchased products!');

    }
}
