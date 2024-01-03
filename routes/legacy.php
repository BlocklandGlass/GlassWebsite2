<?php

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

Route::get('/login.php', function () {
    return redirect()->route('home', $status = 301);
});

Route::get('/dl.php', function () {
    return redirect()->route('addons.addon', ['id' => 11], $status = 301);
});

Route::get('/addons/addon.php', function () {
    if (! request()->has('id')) {
        return redirect()->route('addons', $status = 301);
    }

    $id = request('id');

    return redirect()->route('addons.addon', ['id' => $id], $status = 301);
});

Route::get('/addons/download.php', function () {
    if (! request()->has('id')) {
        return redirect()->route('addons', $status = 301);
    }

    $id = request('id');

    return redirect()->route('addons.addon', ['id' => $id], $status = 301);
});

Route::get('/addons/boards.php', function () {
    return redirect()->route('addons.boards', $status = 301);
});

Route::get('/addons/board.php', function () {
    if (! request()->has('id')) {
        return redirect()->route('addons.boards', $status = 301);
    }

    $id = request('id');

    return redirect()->route('addons.board', ['id' => $id], $status = 301);
});
