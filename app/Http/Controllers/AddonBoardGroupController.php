<?php

namespace App\Http\Controllers;

use App\Models\AddonBoardGroup;
use Illuminate\View\View;

class AddonBoardGroupController extends Controller
{
    /**
     * Show the add-on boards.
     */
    public function show(): View
    {
        return view('addons.boards.index', [
            'addonBoardGroups' => AddonBoardGroup::all(),
        ]);
    }
}
