<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleLoginController extends Controller
{

    public function logout(Request $request) {

        session('g_token' , '' );
        Auth::logout();
        $request->session()->invalidate();

        return redirect('/');

    }
}
