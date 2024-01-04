<?php

namespace App\Http\Controllers;

use App\Models\Blid;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Show the user page.
     */
    public function show(int $id): View
    {
        $user = Blid::where('id', $id)->firstOrFail();

        return view('user.index', [
            'user' => $user,
        ]);
    }
}
