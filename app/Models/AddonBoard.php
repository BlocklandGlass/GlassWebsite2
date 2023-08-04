<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class AddonBoard extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'addon_board_group_id',
        'name',
        'icon',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     * Get the add-on board's add-ons.
     */
    public function addons(): HasMany
    {
        return $this->hasMany(Addon::class);
    }

    /**
     * Get the add-on board's approved add-ons.
     */
    public function getApprovedAddonsAttribute(): Collection
    {
        $addons = Addon::where(function (Builder $query) {
            $query->select('review_status')
                ->from('addon_uploads')
                ->whereColumn('addon_uploads.addon_id', 'addons.id')
                ->where('addon_uploads.review_status', 'approved')
                ->latest()
                ->take(1);
        }, 'approved')
            ->where('addon_board_id', $this->id)
            ->orderByDesc('created_at')
            ->get();

        return $addons;
    }

    /**
     * Get the add-on board's approved add-ons (paginated).
     */
    public function getApprovedAddonsPaginatedAttribute(): LengthAwarePaginator
    {
        $addons = Addon::where(function (Builder $query) {
            $query->select('review_status')
                ->from('addon_uploads')
                ->whereColumn('addon_uploads.addon_id', 'addons.id')
                ->where('addon_uploads.review_status', 'approved')
                ->latest()
                ->take(1);
        }, 'approved')
            ->where('addon_board_id', $this->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return $addons;
    }

    /**
     * Get the add-on board's approved add-ons count.
     */
    public function getApprovedAddonsCountAttribute(): int
    {
        $addons = Addon::where(function (Builder $query) {
            $query->select('review_status')
                ->from('addon_uploads')
                ->whereColumn('addon_uploads.addon_id', 'addons.id')
                ->where('addon_uploads.review_status', 'approved')
                ->latest()
                ->take(1);
        }, 'approved')
            ->where('addon_board_id', $this->id)
            ->count();

        return $addons;
    }
}
