<?php

use App\Http\Controllers\AddonBoardController;
use App\Http\Controllers\AddonBoardGroupController;
use App\Http\Controllers\AddonController;
use App\Http\Controllers\AddonSearchController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MyAccountController;
use App\Http\Controllers\MyAccountLinkController;
use App\Http\Controllers\SteamAuthController;
use App\Http\Controllers\UserBlidController;
use App\Http\Controllers\UserSteamIdController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'index')->name('home');

Route::view('/addons', 'addons.index')->name('addons');

Route::get('/addons/addon', function () {
    return redirect()->route('addons');
});
Route::get('/addons/addon/{id}', [AddonController::class, 'show'])->where('id', '\d+')->name('addons.addon');
Route::get('/addons/download/{id}', function ($id) {
    return redirect()->route('addons.addon', ['id' => $id]);
})->where('id', '\d+');
Route::post('/addons/download/{id}', [AddonController::class, 'download'])->where('id', '\d+')->name('addons.download');

Route::get('/addons/boards', [AddonBoardGroupController::class, 'show'])->name('addons.boards');
Route::view('/addons/rtb', 'addons.rtb.index')->name('addons.rtb');

Route::get('/addons/board', function () {
    return redirect()->route('addons.boards');
});
Route::get('/addons/board/{id}', [AddonBoardController::class, 'show'])->where('id', '\d+')->name('addons.board');

Route::get('/addons/search', [AddonSearchController::class, 'show'])->name('addons.search');

Route::view('/news', 'news.index')->name('news');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::get('/login/auth', [SteamAuthController::class, 'login'])->name('login.auth');
});

Route::middleware('auth')->group(function () {
    Route::get('/my-account', [MyAccountController::class, 'show'])->name('my-account');

    Route::get('/my-account/link', [MyAccountLinkController::class, 'show'])->name('my-account.link');
    Route::post('/my-account/link', [MyAccountLinkController::class, 'store']);

    Route::get('/logout', [SteamAuthController::class, 'logout'])->name('logout');
});

Route::get('/users/blid/{id}', [UserBlidController::class, 'show'])->where('id', '\d+')->name('users.blid');
Route::get('/users/steamid/{id}', [UserSteamIdController::class, 'show'])->where('id', '\d+')->name('users.steamid');

Route::fallback(function () {
    return view('errors.404');
});
