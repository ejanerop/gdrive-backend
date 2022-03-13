<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoogleLoginController extends Controller
{

    public function logout(Request $request , $api_token) {

        $user = User::where('api_token' , $api_token)->first();
        session('g_token' , '' );
        $request->session()->invalidate();

        if ($user) {
            $user->api_token = null;
            $user->save();
            return response()->json('Sesión cerrada con éxito', 204);
        }

        return response()->json($api_token, 200);

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
        $user->api_token = $userData->token;
        $user->save();

        Auth::guard('api')->setUser($user);

        return redirect(env('FRONT_END_URL') . '/login' . '/' . $userData->token );

    }
}
