<?php

use App\Http\Controllers\ApiV2Controller;
use App\Http\Controllers\ApiV3Controller;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/2', [ApiV2Controller::class, 'index'])->name('api.v2.index');
Route::get('/3', [ApiV3Controller::class, 'index'])->name('api.v3.index');

Route::get('/3/auth.php', [ApiV3Controller::class, 'auth'])->name('api.v3.auth');
Route::post('/3/auth.php', [ApiV3Controller::class, 'unfinished']);

Route::get('/3/authCheck.php', [ApiV3Controller::class, 'authCheck'])->name('api.v3.authCheck');

Route::get('/3/bugReport.php', [ApiV3Controller::class, 'unfinished'])->name('api.v3.bugReport');

Route::get('/2/changelog.php', [ApiV2Controller::class, 'changelog'])->name('api.v2.changelog');
Route::get('/3/changelog.php', [ApiV3Controller::class, 'unfinished'])->name('api.v3.changelog');

Route::get('/3/daa.php', [ApiV3Controller::class, 'unfinished'])->name('api.v3.daa');
Route::post('/3/daa.php', [ApiV3Controller::class, 'unfinished']);

Route::get('/3/docs.php', [ApiV3Controller::class, 'docs'])->name('api.v3.docs');

Route::get('/2/download.php', [ApiV2Controller::class, 'download'])->name('api.v2.download');
Route::get('/3/download.php', [ApiV3Controller::class, 'download'])->name('api.v3.download');

Route::get('/3/joinIp.php', [ApiV3Controller::class, 'joinIp'])->name('api.v3.joinIp');

Route::get('/3/mm.php', [ApiV3Controller::class, 'mm'])->name('api.v3.mm');

Route::get('/2/repository.php', [ApiV2Controller::class, 'repository'])->name('api.v2.repository');
Route::get('/3/repository.php', [ApiV3Controller::class, 'unfinished'])->name('api.v3.repository'); // This isn't used in the client at all.

Route::get('/3/serverStats.php', [ApiV3Controller::class, 'unfinished'])->name('api.v3.serverStats');

Route::get('/3/stats.php', [ApiV3Controller::class, 'unfinished'])->name('api.v3.stats');
Route::post('/3/stats.php', [ApiV3Controller::class, 'unfinished']);

Route::fallback([ApiV3Controller::class, 'nonexistent']);
