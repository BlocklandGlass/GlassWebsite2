<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the login page.
     */
    public function show(): View
    {
        return view('login.index');
    }
}
