<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoogleLoginController extends Controller
{

    public function logout(Request $request) {

        session('g_token' , '' );
        Auth::guard()->logout();
        $request->session()->invalidate();

        return redirect('/');

    }

    public function login() {
        $parameters=['access_type' =>'offline'];
        return Socialite::driver('google')->scopes(
            [
                'https://www.googleapis.com/auth/drive',
                'https://www.googleapis.com/auth/drive.file',
                'https://www.googleapis.com/auth/drive.metadata'
            ])->with($parameters)->redirect();
    }

    public function callback() {

        $userData = Socialite::driver('google')->stateless()->user();

        $user = User::where('email' , $userData->email)->first();
        if (!$user) {
            $user = new User();
        }
        $user->name = $userData->name;
        $user->email = $userData->email;
        $user->refresh_token = $userData->token;
        $user->save();

        Auth::login($user);

        return $userData->token;

        return redirect('/');

    }
}
