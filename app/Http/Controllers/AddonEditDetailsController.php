<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\AddonBoard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AddonEditDetailsController extends Controller
{
    /**
     * Show the add-on edit details page.
     */
    public function show(int $id): View|Response
    {
        $addon = Addon::where('id', $id)->withTrashed()->first();

        if (! $addon) {
            return response()->view('addons.addon.error', [
                'title' => 'Not Found',
                'message' => 'This add-on does not exist.',
            ], 404);
        }

        if ($addon->deleted_at) {
            return view('addons.addon.error', [
                'title' => 'Not Available',
                'message' => 'This add-on is no longer available.',
            ]);
        }

        $boards = AddonBoard::whereNot('name', 'Bargain Bin')->get();

        $completed = [
            'screenshots' => $addon->addon_screenshots?->count() > 0 ?? false,
            'file' => false, // TODO: Detect if file is uploaded.
        ];

        return view('addons.edit.details')->with([
            'addon' => $addon,
            'boards' => $boards,
            'completed' => $completed,
            'maxCharacterLength' => 2000,
            'maxScreenshots' => 3,
            'maxFileSize' => 10000,
            'maxScreenshotSize' => 100,
        ]);
    }

    /**
     * TODO: Write function description.
     */
    public function store(int $id): RedirectResponse
    {
        $addon = Addon::where('id', $id)->withTrashed()->first();

        if (! $addon) {
            return back()->withErrors([
                'This add-on does not exist.',
            ]);
        }

        if ($addon->deleted_at) {
            return back()->withErrors([
                'This add-on is no longer available.',
            ]);
        }

        $bargainBinId = AddonBoard::where('name', 'Bargain Bin')->first()->id;

        request()->validate([
            'board' => 'required|integer|exists:App\Models\AddonBoard,id|not_in:'.$bargainBinId,
            'name' => 'required|string|max:50',
            'summary' => 'required|string|max:80',
            'description' => 'required|string|max:2000',
        ]);

        $addon->addon_board_id = request('board');
        $addon->name = request('name');
        $addon->summary = request('summary');
        $addon->description = request('description');

        $addon->save();

        return back()->with('success', 'Your changes have been saved.');
    }
}
