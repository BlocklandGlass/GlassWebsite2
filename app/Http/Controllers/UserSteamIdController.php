<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserSteamIdController extends Controller
{
    /**
     * Show the user page.
     */
    public function show(int $id): View
    {
        $user = User::where('steam_id', $id)->firstOrFail();

        return view('users.index', [
            'user' => $user,
        ]);
    }
}
