<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\AddonUpload;
use App\Traits\ZipTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AddonEditFileController extends Controller
{
    use ZipTrait;

    protected $maxFileSize = 20; // MB

    /**
     * Show the add-on edit file page.
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

        return view('addons.edit.file')->with([
            'addon' => $addon,
            'completed' => $completed,
            'maxFileSize' => $this->maxFileSize,
        ]);
    }

    /**
     * TODO: Write function description.
     */
    public function store(int $id): RedirectResponse
    {
        if (config('app.env') === 'production') {
            return back()->withErrors([
                'This feature is not ready yet.',
            ]);
        }

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

        $file = request()->file('file');
        $fileName = $file->getClientOriginalName();

        $unique = ! (AddonUpload::where('file_name', $fileName)->exists());

        if (! $unique) {
            return back()->withErrors([
                'This add-on\'s file name is already in use.',
            ]);
        }

        $path = Storage::disk('addons')->putFile('uploads', $file);

        $zip = new \ZipArchive();

        if ($zip->open(Storage::disk('addons')->path($path), \ZipArchive::CHECKCONS) !== true) {
            Storage::disk('addons')->delete($path);

            return back()->withErrors([
                'The .zip file could not be opened.',
            ]);
        }

        $tmpSrcPath = 'tmp/'.$fileName;
        $tmpSrcPath2 = Storage::path($tmpSrcPath);
        $tmpDestPath = 'tmp/injected/'.$fileName;
        $tmpDestPath2 = Storage::path($tmpDestPath);

        if (! Storage::exists('tmp/injected')) {
            Storage::makeDirectory('tmp/injected');
        }

        if (Storage::exists($tmpSrcPath)) {
            Storage::deleteDirectory($tmpSrcPath);
        }

        $zip->extractTo($tmpSrcPath2);

        $zip->close();

        if (Storage::exists($tmpSrcPath.'/version.json') || Storage::exists($tmpSrcPath.'/version.txt')) {
            Storage::disk('addons')->delete($path);
            Storage::deleteDirectory($tmpSrcPath);

            return back()->withErrors([
                'You must remove the existing version file.',
            ])->withInput();
        }

        $versionJson = [
            'version' => implode('.', [1, 0, 0]), // TODO: Versioning.
            'channel' => 'stable',
            'repositories' => [
                [
                    'url' => str_ireplace('https', 'http', route('api.v2.repository')),
                    'format' => 'JSON',
                    'id' => $id,
                ],
            ],
        ];

        Storage::put($tmpSrcPath.'/version.json', json_encode($versionJson, JSON_PRETTY_PRINT));

        $glassJson = [
            'formatVersion' => 2,
            'id' => $id,
            'title' => $addon->name,
            'filename' => $fileName,
        ];

        Storage::put($tmpSrcPath.'/glass.json', json_encode($glassJson));

        $this->zip($tmpSrcPath2, $tmpDestPath2);

        Storage::deleteDirectory($tmpSrcPath);

        Storage::disk('addons')->delete($path);

        Storage::move($tmpDestPath, 'addons/'.$path);

        Storage::delete($tmpDestPath);

        AddonUpload::create([
            'addon_id' => $id,
            'file_name' => $fileName,
            'file_size' => Storage::disk('addons')->size($path),
            'file_path' => $path,
            'version' => '1.0.0', // TODO: Versioning.
            'restart_required' => false,
        ]);

        return back();
    }
}
