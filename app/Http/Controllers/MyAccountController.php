<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class MyAccountController extends Controller
{
    /**
     * Show the account page.
     */
    public function show(): View
    {
        return view('my-account.index');
    }
}
