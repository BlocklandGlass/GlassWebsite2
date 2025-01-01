<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\AddonBoard;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AddonUploadController extends Controller
{
    /**
     * Show the add-on upload page.
     */
    public function show(): View
    {
        $boards = AddonBoard::whereNot('name', 'Bargain Bin')->get();

        return view('addons.upload.index')->with([
            'boards' => $boards,
            'maxCharacterLength' => 2000,
            'maxScreenshots' => 3,
            'maxFileSize' => 10000,
            'maxScreenshotSize' => 100,
        ]);
    }

    /**
     * TODO: Write function description.
     */
    public function store(): RedirectResponse
    {
        $bargainBinId = AddonBoard::where('name', 'Bargain Bin')->first()->id;

        request()->validate([
            'board' => 'required|integer|exists:App\Models\AddonBoard,id|not_in:'.$bargainBinId,
            'name' => 'required|string|max:50',
            'summary' => 'required|string|max:80',
            'description' => 'required|string|max:2000',
        ]);

        $addon = Addon::create([
            'addon_board_id' => request('board'),
            'blid_id' => request()->user()->primary_blid,
            'name' => request('name'),
            'summary' => request('summary'),
            'description' => request('description'),
            'is_draft' => true,
        ]);

        return redirect()->route('addons.edit.screenshots', ['id' => $addon->id])->with('success', 'Your add-on is now in a draft state, please complete the rest of the sections and go to Publish when ready.');
    }
}
