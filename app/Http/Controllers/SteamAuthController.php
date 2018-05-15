<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Invisnik\LaravelSteamAuth\SteamAuth;
use App\User;
use Auth;
use PHPUnit\Framework\Exception;

class SteamAuthController extends Controller
{
    /**
     * The SteamAuth instance.
     *
     * @var SteamAuth
     */
    protected $steam;

    /**
     * The redirect URL.
     *
     * @var string
     */
    protected $redirectURL = '/profile';

    /**
     * AuthController constructor.
     *
     * @param SteamAuth $steam
     */
    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    /**
     * Redirect the user to the authentication page
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectToSteam()
    {
        return $this->steam->redirect();
    }

    /**
     * Get user info and log in
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handle(Request $request)
    {
        if ($this->steam->validate()) {
            $info = $this->steam->getUserInfo();
            try {
//                $request->user()->update([
//                   'player_id' => $info->steamID64
//                ]);
                $request->user()->update(['player_id' => $info->steamID64]);
                $request->user()->stats()->insert([
                    'user_id' => $info->steamID64
                ]);
                } catch (\Exception $e) {
                return redirect($this->redirectURL)->with('errors', 'User with this player_id already registered');
            }

            return redirect($this->redirectURL); // redirect to site
        }
        return $this->redirectToSteam();
    }
//
//    /**
//     * Getting user by info or created if not exists
//     *
//     * @param $info
//     * @return User
//     */
//    protected function findOrNewUser($info)
//    {
//        $user = User::where('steamid', $info->steamID64)->first();
//
//        if (!is_null($user)) {
//            return $user;
//        }
//
//        return User::create([
//            'username' => $info->personaname,
//            'avatar' => $info->avatarfull,
//            'steamid' => $info->steamID64
//        ]);
//    }
}
