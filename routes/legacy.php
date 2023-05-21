<?php

use Illuminate\Support\Facades\Request;
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
    return redirect()->route('home');
});

Route::get('/dl.php', function () {
    return redirect()->route('addons.addon', ['id' => 11]);
});

Route::get('/addons/addon.php', function () {
    if (! Request::has('id')) {
        return redirect()->route('addons');
    }

    $id = Request::get('id');

    return redirect()->route('addons.addon', ['id' => $id]);
});

Route::get('/addons/download.php', function () {
    if (! Request::has('id')) {
        return redirect()->route('addons');
    }

    $id = Request::get('id');

    return redirect()->route('addons.addon', ['id' => $id]);
});

Route::get('/addons/boards.php', function () {
    return redirect()->route('addons.boards');
});

Route::get('/addons/board.php', function () {
    if (! Request::has('id')) {
        return redirect()->route('addons.boards');
    }

    $id = Request::get('id');

    return redirect()->route('addons.board', ['id' => $id]);
});
