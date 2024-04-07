<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiV2Controller extends Controller
{
    /**
     * TODO: Write function description.
     */
    public function index(): string
    {
        return 'api.blocklandglass.com (v2)';
    }

    /**
     * TODO: Write function description.
     */
    public function changelog(): string
    {
        $id = request('id');

        $addon = Addon::where('id', $id)->first();

        if (! $addon) {
            return '';
        }

        if ($addon->is_draft) {
            return '';
        }

        $addonUpload = $addon->latest_approved_addon_upload;

        if (! $addonUpload) {
            return '';
        }

        $changelog = trim($addonUpload->changelog);

        if ($changelog === '') {
            $changelog = 'No changelog was written.';
        }

        return $changelog;
    }

    /**
     * TODO: Write function description.
     */
    public function download(): StreamedResponse|string
    {
        $type = request('type');

        if ($type === 'addon_update' || $type === 'addon_download') {
            $id = request('id');

            $addon = Addon::where('id', $id)->first();

            if (! $addon) {
                return ''; // TODO: Better error handling.
            }

            if ($addon->is_draft) {
                return ''; // TODO: Better error handling.
            }

            $addonUpload = $addon->latest_approved_addon_upload;

            if (! $addonUpload) {
                return ''; // TODO: Better error handling.
            }

            $available = Storage::disk('addons')->exists($addonUpload->file_path);

            if (! $available) {
                return ''; // TODO: Better error handling.
            }

            $ipAddress = request()->ip();

            if ($type === 'addon_update') {
                $addonUploadStatistic = $addonUpload->addStatistic('update', $ipAddress);
            } else {
                $addonUploadStatistic = $addonUpload->addStatistic('ingame', $ipAddress);
            }

            if (! $addonUploadStatistic) {
                return ''; // TODO: Better error handling.
            }

            /*
             * The in-game client subtracts 1 character on the beginning and end of the file name.
             * Laravel does not surround the file name with quotations, so we need to manually add
             * them to appease the client or else you get add-ons downloaded as "eapon_Gun.zi"
             */
            return Storage::disk('addons')->download($addonUpload->file_path, $addonUpload->file_name, headers: [
                'Content-Disposition' => 'attachment; filename="'.$addonUpload->file_name.'"',
            ]);
        }

        return '';
    }

    /*
     * TODO: Write function description.
     */
    public function repository(): string
    {
        $data = [];

        $mods = request('mods', '');

        if ($mods === '') {
            $data['status'] = 'error';
            $data['error'] = 'Mods field is blank.';

            return json_encode($data, JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        $addonIds = explode('-', $mods);

        $data['name'] = 'Blockland Glass';
        $data['add-ons'] = [];

        foreach ($addonIds as $addonId) {
            $addon = Addon::where('id', $addonId)->first();

            if (! $addon) {
                continue;
            }

            if ($addon->is_draft) {
                continue;
            }

            $addonUpload = $addon->latest_approved_addon_upload;

            if (! $addonUpload) {
                continue;
            }

            $available = Storage::disk('addons')->exists($addonUpload->file_path);

            if (! $available) {
                continue;
            }

            $name = 'stable';
            $legacy = request('legacy', '');

            if ($legacy === '1' && $addon->id !== 11) {
                $name = '*';
            }

            $data['add-ons'][] = [
                'name' => pathinfo($addonUpload->file_name, PATHINFO_FILENAME), // If the file name does not match an add-on (regardless of the ID in the version.json), the update won't be seen by Support_Updater.
                'description' => $addon->summary,
                'channels' => [
                    [
                        'name' => $name, // If the channel name does not match an add-on's version.json, the update won't be seen by Support_Updater.
                        'version' => $addonUpload->version,
                        'restartRequired' => $addonUpload->restart_required,
                        'file' => str_ireplace('https://', 'http://', route('api.v2.download', ['id' => $addon->id, 'type' => 'addon_update'])), // Blockland must have these links returned as HTTP and Laravel Octane (which rewrites everything to HTTPS) makes it so we have to do it like this.
                        'changelog' => str_ireplace('https://', 'http://', route('api.v2.changelog', ['id' => $addon->id])),
                    ],
                ],
            ];
        }

        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
