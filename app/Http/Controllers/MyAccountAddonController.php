<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class MyAccountAddonController extends Controller
{
    /**
     * Show the account add-ons page.
     */
    public function show(): View
    {
        return view('my-account.my-addons.index');
    }
}
