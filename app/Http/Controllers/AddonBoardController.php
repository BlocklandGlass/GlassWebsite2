<?php

namespace App\Http\Controllers;

use App\Models\AddonBoard;
use Illuminate\View\View;

class AddonBoardController extends Controller
{
    /**
     * Show the add-on board.
     */
    public function show(int $id): View
    {
        $addonBoard = AddonBoard::findOrFail($id);

        return view('addons.board.index', [
            'addonBoard' => $addonBoard,
            'approvedAddons' => $addonBoard->approved_addons_paginated,
        ]);
    }
}
