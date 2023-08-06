<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AddonSearchController extends Controller
{
    /**
     * Show the add-on boards.
     */
    public function show(): View|RedirectResponse
    {
        if (! request()->has('query')) {
            return redirect()->route('addons.boards');
        }

        $query = trim(request('query'));

        if ($query === '') {
            return redirect()->route('addons.boards');
        }

        $approvedAddons = Addon::where(function (Builder $query) {
            $query->select('review_status')
                ->from('addon_uploads')
                ->whereColumn('addon_uploads.addon_id', 'addons.id')
                ->where('addon_uploads.review_status', 'approved')
                ->latest()
                ->take(1);
        }, 'approved')
            ->where('name', 'like', '%'.$query.'%')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('addons.search.index', [
            'query' => $query,
            'approvedAddons' => $approvedAddons,
        ]);
    }
}
