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

        $upload = $addon->addon_uploads?->last();

        $completed = [
            'screenshots' => $addon->addon_screenshots?->count() > 0 ?? false,
            'file' => $upload !== null,
        ];

        return view('addons.edit.file')->with([
            'addon' => $addon,
            'upload' => $upload,
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

        if (! $addon->is_draft) { // TODO: Updates ( && $addon->latest_approved_addon_upload === null )
            return back()->withErrors([
                'This add-on is not in a draft state.',
            ]);
        }

        $disk = Storage::disk('addons');

        $upload = $addon->addon_uploads?->last();

        if (request('delete') === '1') {
            if (! $addon->is_draft) {
                return back()->withErrors([
                    'This add-on is not in a draft state.',
                ]);
            }

            if ($upload === null) {
                return back()->withErrors([
                    'The file does not exist.',
                ]);
            }

            $disk->delete($upload->file_path);
            $upload->delete();

            return back()->with('success', 'The file has been deleted.');
        }

        if ($upload !== null && $upload->review_status === 'pending') {
            return back()->withErrors([
                'The file is already pending review.',
            ]);
        }

        request()->validate([
            'version' => ['semver'],
            'version.*' => ['required'],
            'file' => ['required', 'max:'.($this->maxFileSize * 1000), 'mimetypes:application/zip', 'addon', 'defaultaddon', 'uniqueaddon'],
        ], [
            'version.semver' => 'The version number is invalid.',
            'file.required' => 'The file is missing.',
            'file.max' => 'The file is too big.',
            'file.mimetypes' => 'The file is not a .zip file.',
            'file.addon' => 'The file name is not in the correct format.',
            'file.defaultaddon' => 'The file name is already in use by a default add-on.',
            'file.uniqueaddon' => 'The file name is already in use by another add-on.',
        ]);

        $file = request()->file('file');
        $fileName = $file->getClientOriginalName();

        $tmpFilePath = $disk->putFile('tmp', $file);
        $tmpFolderPath = $tmpFilePath.'_extracted';

        $newFileName = basename($tmpFilePath);

        if (! $tmpFilePath) {
            return back()->withErrors([
                'The file could not be saved.',
            ]);
        }

        $fullTmpFilePath = Storage::disk('addons')->path($tmpFilePath);
        $fullTmpFolderPath = $fullTmpFilePath.'_extracted';

        $zip = new \ZipArchive;

        if ($zip->open($fullTmpFilePath, \ZipArchive::CHECKCONS) !== true) {
            $disk->delete($tmpFilePath);

            return back()->withErrors([
                'The file could not be opened.',
            ]);
        }

        $zip->extractTo($fullTmpFolderPath);
        $zip->close();

        $errors = $this->scanAndClean($fullTmpFolderPath);

        if ($disk->fileExists($tmpFolderPath.'/version.json') || $disk->fileExists($tmpFolderPath.'/version.txt')) {
            $errors[] = 'You must remove the existing version.json and/or version.txt file.';
        }

        if (! $disk->fileExists($tmpFolderPath.'/description.txt')) {
            $errors[] = 'You must add a description.txt file.';
        }

        if (count($errors) > 0) {
            $disk->delete($tmpFilePath);
            $disk->deleteDirectory($tmpFolderPath);

            return back()->withErrors($errors);
        }

        $version = request('version');
        $version = $version['major'].'.'.$version['minor'].'.'.$version['patch'];

        $versionJson = [
            'version' => $version,
            'channel' => 'stable',
            'repositories' => [
                [
                    'url' => str_ireplace('https://', 'http://', route('api.v2.repository')),
                    'format' => 'JSON',
                    'id' => $id,
                ],
            ],
        ];

        if (! $disk->put($tmpFolderPath.'/version.json', json_encode($versionJson, JSON_PRETTY_PRINT))) {
            $disk->delete($tmpFilePath);
            $disk->deleteDirectory($tmpFolderPath);

            return back()->withErrors([
                'Failed to create version.json',
            ]);
        }

        $glassJson = [
            'formatVersion' => 2,
            'id' => $id,
            'title' => $addon->name,
            'filename' => $fileName,
        ];

        if (! $disk->put($tmpFolderPath.'/glass.json', json_encode($glassJson))) {
            $disk->delete($tmpFilePath);
            $disk->deleteDirectory($tmpFolderPath);

            return back()->withErrors([
                'Failed to create glass.json',
            ]);
        }

        $newFullFilePath = $disk->path($newFileName);

        $this->zip($fullTmpFolderPath, $newFullFilePath);

        $disk->delete($tmpFilePath);
        $disk->deleteDirectory($tmpFolderPath);

        AddonUpload::create([
            'addon_id' => $id,
            'file_name' => $fileName,
            'file_size' => Storage::disk('addons')->size($newFileName),
            'file_path' => $newFileName,
            'version' => $version,
            'restart_required' => false,
            'changelog' => $addon->latest_approved_addon_upload === null ? 'Initial upload.' : '', // TODO: Changelogs.
            'review_status' => 'pending',
        ]);

        return back()->with('success', 'The file has been uploaded.');
    }

    /**
     * TODO: Write function description.
     */
    private function scanAndClean(string $fullFolderPath): array
    {
        $errors = [];

        $badExtensions = [
            'exe',
            'sh',
            'ps1',
            'psm1',
            'bat',
            'cmd',
            'vbs',
            'js',
            'wsf',
            'hta',
            'py',
            'msi',
            'dll',
            'com',
            'scr',
            'jar',
            'reg',
            'lnk',
            'url',
            'html',
            'htm',
            'iso',
            'apk',
            'zip',
            '7z',
            'rar',
            'gz',
            'bz2',
            'tgz',
            'tar',
            'iso',
            'cab',
            'deb',
        ];

        $dirsPendingDeletion = [];
        $filesPendingDeletion = [];

        $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($fullFolderPath, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($objects as $object) {
            $objectName = strtolower($object->getFilename());

            $objectPath = $object->getPathname();

            if (is_dir($object)) {
                if ($objectName === '__macosx') {
                    $dirsPendingDeletion[] = $objectPath;
                }
            } else {
                if ($objectName === 'thumbs.db' || $objectName === '.ds_store') {
                    $filesPendingDeletion[] = $objectPath;

                    continue;
                }

                $extension = strtolower($object->getExtension());

                if (in_array($extension, $badExtensions)) {
                    $errors[] = $object->getFilename().' is not allowed.';
                }
            }
        }

        foreach ($dirsPendingDeletion as $path) {
            $this->rrmdir($path);
        }

        foreach ($filesPendingDeletion as $path) {
            unlink($path);
        }

        return $errors;
    }

    /**
     * TODO: Write function description.
     */
    private function rrmdir(string $fullFolderPath): void
    {
        if ($fullFolderPath === '' || $fullFolderPath === DIRECTORY_SEPARATOR) {
            return;
        }

        if (is_dir($fullFolderPath)) {
            $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($fullFolderPath, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($objects as $object) {
                if (is_dir($object)) {
                    rmdir($object);
                } else {
                    unlink($object);
                }
            }

            rmdir($fullFolderPath);
        }
    }
}
