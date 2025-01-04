<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AddonController extends Controller
{
    /**
     * Show the add-on page.
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

        if ($addon->is_draft) {
            return view('addons.addon.error', [
                'title' => 'Not Published',
                'message' => 'This add-on has not been published yet.',
            ]);
        }

        $addonUpload = $addon->latest_approved_addon_upload;

        if ($addonUpload === null) {
            return view('addons.addon.error', [
                'title' => 'Pending Review',
                'message' => 'This add-on is currently pending review.',
            ]);
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

        if ($addon->is_draft) {
            abort(404);
        }

        $addonUpload = $addon->latest_approved_addon_upload;

        if ($addonUpload === null) {
            abort(404);
        }

        $available = Storage::disk('addons')->exists($addonUpload->file_path);

        if (! $available) {
            abort(404);
        }

        $ipAddress = request()->ip();

        $addonUploadStatistic = $addonUpload->addStatistic('web', $ipAddress);

        if (! $addonUploadStatistic) {
            abort(500); // TODO: Better error handling.
        }

        return Storage::disk('addons')->download($addonUpload->file_path, $addonUpload->file_name);
    }
}
