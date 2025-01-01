<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\AddonScreenshot;
use App\Traits\ZipTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AddonEditScreenshotsController extends Controller
{
    use ZipTrait;

    protected $maxScreenshots = 3;

    protected $maxScreenshotSize = 3.5; // MB

    /**
     * Show the add-on edit media page.
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

        $completed = [
            'screenshots' => $addon->addon_screenshots?->count() > 0 ?? false,
            'file' => false, // TODO: Detect if file is uploaded.
        ];

        return view('addons.edit.screenshots')->with([
            'addon' => $addon,
            'completed' => $completed,
            'maxScreenshots' => $this->maxScreenshots,
            'maxScreenshotSize' => $this->maxScreenshotSize,
            'limited' => false,
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

        if (($addon->addon_screenshots?->count() ?? 0) >= $this->maxScreenshots) {
            return back()->withErrors([
                'The maximum number of screenshots has been reached.',
            ]);
        }

        request()->validate([
            'screenshot' => 'required|max:'.($this->maxScreenshotSize * 1000).'|mimetypes:image/png,image/jpeg',
        ]);

        $file = request()->file('screenshot');

        $path = Storage::disk('public')->putFile('screenshots', $file);

        AddonScreenshot::create([
            'addon_id' => $addon->id,
            'file_path' => $path,
            'display_order' => $addon->addon_screenshots?->count() ?? 0,
        ]);

        return back()->with('success', 'The screenshot has been uploaded.');
    }

    /**
     * TODO: Write function description.
     */
    public function patch(int $id): RedirectResponse
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

        request()->validate([
            'action' => ['required', 'in:>,<'],
        ]);

        $screenshot = AddonScreenshot::findOrFail(request('id'));

        if (request('action') === '<') {
            $screenshot->display_order -= 1.5;
        } else {
            $screenshot->display_order += 1.5;
        }

        $screenshot->update();

        $addon->sortScreenshots();

        return back();
    }

    /**
     * TODO: Write function description.
     */
    public function delete(int $id): RedirectResponse
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

        $screenshot = AddonScreenshot::findOrFail(request('id'));

        $screenshot->delete();

        Storage::disk('public')->delete($screenshot->file_path);

        $addon->sortScreenshots();

        return back()->with('success', 'The screenshot has been deleted.');
    }
}
