<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\AddonBoard;
use App\Models\AddonBoardGroup;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiV3Controller extends Controller
{
    /**
     * TODO: Write function description.
     */
    public function index(): string
    {
        return 'api.blocklandglass.com (v3)';
    }

    /**
     * TODO: Write function description.
     */
    public function auth(): string
    {
        $data = [
            'ident' => 'anonymous',
            'status' => 'success',
        ];

        return json_encode($data, JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * TODO: Write function description.
     */
    public function authCheck(): string
    {
        $data = [
            'ident' => 'anonymous',
            'blid' => '888888',
            'username' => 'Anonymous',
            'admin' => false,
            'mod' => false,
            'beta' => false,
            'geoip_country_name' => '',
            'geoip_country_code' => '',
            'status' => 'success',
        ];

        return json_encode($data, JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * TODO: Write function description.
     */
    public function docs(): string
    {
        $allowed = [ // Let's not have a repeat of the path traversal exploit.
            '1 Credits',
            '1.1 Staff List',
            '2 Usage',
            '2.1 Mod Manager',
            '3 Dev Docs',
            '3.1 Preferences',
            '3.2 Required Clients',
            '3.3 Loading Screens',
            '3.4 Player List',
        ];

        if (Request::has('doc')) {
            $doc = Request::get('doc');

            if (! in_array($doc, $allowed)) {
                return 'Invalid file.';
            }

            $doc .= '.txt';

            if (! Storage::disk('docs')->exists($doc)) {
                return 'File not found.';
            }

            return Storage::disk('docs')->get($doc);
        } else {
            return implode(".txt\n", $allowed).".txt\n";
        }
    }

    /**
     * TODO: Write function description.
     */
    public function download(): StreamedResponse|string
    {
        $type = Request::get('type');

        if ($type === 'addon_update' || $type === 'addon_download') {
            $id = Request::get('id');

            $addon = Addon::where('id', $id)->first();

            if (! $addon) {
                return '';
            }

            $addonUpload = $addon->latest_approved_addon_upload;

            if (! $addonUpload) {
                return '';
            }

            $ipAddress = Request::ip();

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

    /**
     * TODO: Write function description.
     */
    public function joinIp(): string
    {
        $record = dns_get_record('api.blocklandglass.com', DNS_A);

        return $record[0]['ip'];
    }

    /**
     * TODO: Write function description.
     */
    public function mm(): string
    {
        $data = [
            'status' => 'success',
        ];

        switch (Request::get('call')) {
            case 'home':
                $data['data'] = [];

                $message = <<<'MESSAGE'
                    2023-05-21<br>
                    <spush><font:verdana bold:18>And we're back<spop><br>
                    Your client has successfully connected to the new Glass website.<br>
                    Searching for and downloading Glass add-ons both on the new website and in-game should now be operational.<br>
                    Please note: The RTB Archive and other minor features are still in the process of being rewritten (so accessing them won't do anything), and if you aren't already aware, Glass Live isn't coming back either.<br>
                    We will be issuing a client update in due course to remove any redundant features and to fix any known outstanding bugs since the last official update in May 2020.<br>
                    - The Blockland Glass Team
                    MESSAGE;

                $data['data'][] = [
                    'type' => 'message',
                    'message' => $message,
                ];

                $data['data']['length'] = count($data['data']);

                break;
            case 'boards':
                $data['groups'] = [];

                $addonBoardGroups = AddonBoardGroup::get();

                foreach ($addonBoardGroups as $addonBoardGroup) {
                    $addonBoards = $addonBoardGroup->boards;
                    $boards = [];

                    foreach ($addonBoards as $addonBoard) {
                        $boards[] = [
                            'id' => $addonBoard->id,
                            'name' => $addonBoard->name,
                            'icon' => $addonBoard->icon,
                        ];
                    }

                    $data['groups'][] = [
                        'name' => $addonBoardGroup->name,
                        'boards' => $boards + ['length' => count($boards)],
                    ];
                }

                $data['groups']['length'] = count($data['groups']);

                break;
            case 'board':
                $data['addons'] = [];

                $page = Request::get('page', 1);
                $id = Request::get('id');

                if ($id === 'rtb') {
                    // TODO: Finish RTB board.
                    return '';
                }

                $addonBoard = AddonBoard::find($id);

                if ($addonBoard) {
                    $addons = Addon::where(function (Builder $query) {
                        $query->select('review_status')
                            ->from('addon_uploads')
                            ->whereColumn('addon_uploads.addon_id', 'addons.id')
                            ->where('addon_uploads.review_status', 'approved')
                            ->latest()
                            ->take(1);
                    }, 'approved')
                        ->where('addon_board_id', $addonBoard->id)
                        ->latest()
                        ->paginate(10);

                    foreach ($addons as $addon) {
                        $data['addons'][] = [
                            'id' => $addon->id,
                            'name' => $addon->name,
                            'author' => $addon->blid->name,
                            'downloads' => number_format($addon->total_downloads),
                            'summary' => $addon->summary,
                        ];
                    }

                    $data['board_id'] = $addonBoard->id;
                    $data['board_name'] = $addonBoard->name;
                    $data['page'] = $page;
                    $data['pages'] = $addons->lastPage();

                    $data['addons']['length'] = count($data['addons']);
                }

                break;
            case 'addon':
                $id = Request::get('id');

                $addon = Addon::where('id', $id)->withTrashed()->first();

                if (! $addon) {
                    $data['status'] = 'notfound';
                    $data['error'] = 'This add-on does not exist.';
                    break;
                }

                if ($addon->deleted_at) {
                    $data['status'] = 'deleted';
                    $data['error'] = 'This add-on is no longer available.';
                    break;
                }

                $addonUpload = $addon->latest_approved_addon_upload;

                if (! $addonUpload) {
                    $data['status'] = 'notapproved';
                    $data['error'] = 'This add-on has not been approved.';
                    break;
                }

                if (! Storage::disk('addons')->exists($addonUpload->file_path)) {
                    $data['status'] = 'notfound';
                    $data['error'] = 'This add-on\'s file is missing.';
                    break;
                }

                $data['aid'] = $addon->id;
                $data['filename'] = $addon->latest_approved_addon_upload->file_name;
                $data['board_id'] = $addon->addon_board_id;
                $data['board'] = $addon->addon_board->name;
                $data['name'] = $addon->name;
                $data['description'] = $addon->description;
                $data['date'] = $addon->human_readable_created_at;
                $data['downloads'] = number_format($addon->total_downloads);
                $data['author'] = $addon->blid->name;
                $data['screenshots'] = []; // TODO: Screenshots.
                $data['activity'] = [];

                $addonComments = $addon->addon_comments;

                foreach ($addonComments as $addonComment) {
                    $data['activity'][] = [
                        'type' => 'comment',
                        'timestamp' => strtotime($addonComment->created_at),
                        'date' => $addonComment->human_readable_created_at,
                        'author' => $addonComment->blid->name,
                        'authorBlid' => $addonComment->blid->id,
                        'title' => '', // TODO: Role title.
                        'comment' => $addonComment->body,
                    ];
                }

                $addonUploads = $addon->addon_uploads->skip(1);

                foreach ($addonUploads as $addonUpload) {
                    if ($addonUpload->review_status !== 'approved') {
                        continue;
                    }

                    $data['activity'][] = [
                        'type' => 'update',
                        'timestamp' => strtotime($addonUpload->created_at),
                        'date' => $addonUpload->human_readable_created_at,
                        'version' => $addonUpload->version,
                        'changelog' => $addonUpload->changelog,
                    ];
                }

                usort($data['activity'], function ($a, $b) {
                    return $a['timestamp'] < $b['timestamp'] ? -1 : 1;
                });

                $data['screenshots']['length'] = count($data['screenshots']);
                $data['activity']['length'] = count($data['activity']);

                break;
            case 'search':
                $data['results'] = [];

                $name = trim(Request::get('name', ''));
                $author = trim(Request::get('author', ''));
                $boardId = trim(Request::get('board', ''));

                $addons = Addon::where('name', 'like', '%'.$name.'%');

                if ($author !== '') {
                    $addons = $addons->where(function (Builder $query) {
                        $query->select('name')
                            ->from('blids')
                            ->whereColumn('blids.id', 'addons.blid_id')
                            ->take(1);
                    }, 'like', '%'.$author.'%');
                }

                if ($boardId !== '' && $boardId !== '0') {
                    $addons = $addons->where(function (Builder $query) {
                        $query->select('id')
                            ->from('addon_boards')
                            ->whereColumn('addon_boards.id', 'addons.addon_board_id')
                            ->take(1);
                    }, $boardId);
                }

                $addons = $addons->where(function (Builder $query) {
                    $query->select('review_status')
                        ->from('addon_uploads')
                        ->whereColumn('addon_uploads.addon_id', 'addons.id')
                        ->where('addon_uploads.review_status', 'approved')
                        ->latest()
                        ->take(1);
                }, 'approved');

                $addons = $addons->latest()->limit(10)->get();

                foreach ($addons as $addon) {
                    $data['results'][] = [
                        'id' => $addon->id,
                        'title' => $addon->name,
                        'author' => [
                            'username' => $addon->blid->name,
                            'blid' => $addon->blid->id,
                        ],
                        'summary' => $addon->summary,
                    ];
                }

                $data['results']['length'] = count($data['results']);

                break;
            default:
        }

        return json_encode($data, JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * TODO: Write function description.
     */
    public function unfinished(): string
    {
        return json_encode([], JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * TODO: Write function description.
     */
    public function nonexistent(): string
    {
        $data = [
            'status' => 'error',
            'error' => 'This endpoint does not exist.',
        ];

        return json_encode($data, JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
