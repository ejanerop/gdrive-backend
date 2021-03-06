<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleLoginController;
use App\Http\Controllers\FolderController;

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

Route::get('/login', [GoogleLoginController::class, 'login'])->middleware('cors')->name('login');
Route::get('/google/callback', [GoogleLoginController::class, 'callback'])->middleware('cors');
Route::get('/logout/{token}', [GoogleLoginController::class, 'logout'])->middleware('cors')->name('logout');

