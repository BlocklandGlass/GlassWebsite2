<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Show the account page.
     */
    public function show(): View
    {
        return view('account.index');
    }
}
