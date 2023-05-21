<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AddonController extends Controller
{
    /**
     * Show the add-on page.
     */
    public function show(int $id): View
    {
        $addon = Addon::where('id', $id)->firstOrFail();

        $addonUpload = $addon->latest_approved_addon_upload;

        if (! $addonUpload) {
            abort(404);
        }

        $available = Storage::disk('addons')->exists($addonUpload->file_path);

        return view('addons.addon.index', [
            'addon' => $addon,
            'available' => $available,
        ]);
    }

    /**
     * Download the add-on.
     */
    public function download(int $id): StreamedResponse
    {
        $addon = Addon::where('id', $id)->firstOrFail();

        $addonUpload = $addon->latest_approved_addon_upload;

        if (! $addonUpload) {
            abort(404);
        }

        $available = Storage::disk('addons')->exists($addonUpload->file_path);

        if (! $available) {
            abort(404);
        }

        $ipAddress = Request::ip();

        $addonUploadStatistic = $addonUpload->addStatistic('web', $ipAddress);

        if (! $addonUploadStatistic) {
            abort(500); // TODO: Better error handling.
        }

        return Storage::disk('addons')->download($addonUpload->file_path, $addonUpload->file_name);
    }
}
