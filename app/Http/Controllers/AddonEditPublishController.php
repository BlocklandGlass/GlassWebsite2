<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use Illuminate\Http\RedirectResponse;
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
            'file' => $addon->addon_uploads?->last() ?? false,
        ];

        return view('addons.edit.publish')->with([
            'addon' => $addon,
            'completed' => $completed,
        ]);
    }

    /**
     * TODO: Write function description.
     */
    public function store(int $id): RedirectResponse
    {
        $addon = Addon::where('id', $id)->withTrashed()->first();

        if ($addon === null) {
            return back()->withErrors([
                'This add-on does not exist.',
            ]);
        }

        if ($addon->deleted_at) {
            return back()->withErrors([
                'This add-on is no longer available.',
            ]);
        }

        if ($addon->is_draft) {
            $upload = $addon->addon_uploads?->last();

            if ($upload === null) {
                return back()->withErrors([
                    'The add-on does not have a file.',
                ]);
            }

            $addon->is_draft = false;
            $addon->save();

            return back()->with('success', 'The add-on has been published.');
        } else {
            if ($addon->latest_approved_addon_upload === null) {
                $addon->is_draft = true;
                $addon->save();

                return back()->with('success', 'The add-on has been unpublished.');
            } else {
                return back()->withErrors([
                    'The add-on can no longer be unpublished.',
                ]);
            }
        }
    }
}
