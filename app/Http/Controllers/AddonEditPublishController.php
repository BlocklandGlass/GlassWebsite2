<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AddonEditPublishController extends Controller
{
    /**
     * Show the add-on edit publish page.
     */
    public function show(int $id): View|Response
    {
        $addon = Addon::where('id', $id)->withTrashed()->first();

        if ($addon === null) {
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

        $completed = [
            'screenshots' => $addon->addon_screenshots?->count() > 0 ?? false,
            'file' => false, // TODO: Detect if file is uploaded.
        ];

        return view('addons.edit.publish')->with([
            'addon' => $addon,
            'completed' => $completed,
        ]);
    }
}
