<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\GoogleLoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/login', function () {
    $parameters=['access_type' =>'offline'];
    return Socialite::driver('google')->scopes(['https://www.googleapis.com/auth/drive'])->with($parameters)->redirect();
});

Route::get('/google/callback', function () {
    $userData = Socialite::driver('google')->stateless()->user();

    $user = User::where('email' , $userData->email)->first();
    if (!$user) {
        $user = new User();
    }
    $user->name = $userData->name;
    $user->email = $userData->email;
    $user->refresh_token = $userData->token;
    $user->save();

    Auth::login($user, true);

    return redirect('/');
});

Route::get('/logout', [GoogleLoginController::class, 'logout']);

//require __DIR__.'/auth.php';
