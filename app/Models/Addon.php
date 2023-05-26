<?php

namespace App\Models;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Addon extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'addon_board_id',
        'blid_id',
        'name',
        'summary',
        'description',
        'no_index',
        'total_downloads',
        'web_downloads',
        'ingame_downloads',
        'update_downloads',
        'legacy_total_downloads',
        'legacy_web_downloads',
        'legacy_ingame_downloads',
        'legacy_update_downloads',
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
    protected $casts = [
        'no_index' => 'boolean',
    ];

    /**
     * Get the add-on's board.
     */
    public function addon_board(): BelongsTo
    {
        return $this->belongsTo(AddonBoard::class);
    }

    /**
     * Get the add-on's uploads.
     */
    public function addon_uploads(): HasMany
    {
        return $this->hasMany(AddonUpload::class);
    }

    /**
     * Get the add-on's comments.
     */
    public function addon_comments(): HasMany
    {
        return $this->hasMany(AddonComment::class);
    }

    /**
     * Get the add-on's associated BLID.
     */
    public function blid(): BelongsTo
    {
        return $this->belongsTo(Blid::class);
    }

    /*
     * Get the add-on's total downloads w/ legacy total download count added.
     */
    public function getTotalDownloadsAttribute($value)
    {
        return $value + $this->legacy_total_downloads;
    }

    /*
     * Get the add-on's web downloads w/ legacy web download count added.
     */
    public function getWebDownloadsAttribute($value)
    {
        return $value + $this->legacy_web_downloads;
    }

    /*
     * Get the add-on's in-game downloads w/ legacy in-game download count added.
     */
    public function getIngameDownloadsAttribute($value)
    {
        return $value + $this->legacy_ingame_downloads;
    }

    /*
     * Get the add-on's update downloads w/ legacy update download count added.
     */
    public function getUpdateDownloadsAttribute($value)
    {
        return $value + $this->legacy_update_downloads;
    }

    /**
     * Get the add-on's HTML description via Markdown.
     */
    public function getHtmlDescriptionAttribute(): string
    {
        return Markdown::convert($this->description)->getContent();
    }

    /**
     * Get the add-on's created_at in a human-readable format.
     */
    public function getHumanReadableCreatedAtAttribute(): string
    {
        return $this->created_at->format('M jS Y, g:i A');
    }

    /**
     * Get the add-on's latest approved add-on upload.
     */
    public function getLatestApprovedAddonUploadAttribute(): AddonUpload
    {
        return $this->addon_uploads->where('review_status', 'approved')->sortByDesc('created_at')->first();
    }

    /*
     * TODO: Write function description.
     */
    public function updateStatistics(): void
    {
        $webDownloads = 0;
        $ingameDownloads = 0;
        $updateDownloads = 0;

        $addonUploads = $this->addon_uploads;

        foreach ($addonUploads as $addonUpload) {
            $addonUpload->updateStatistics();

            $webDownloads += $addonUpload->web_downloads;
            $ingameDownloads += $addonUpload->ingame_downloads;
            $updateDownloads += $addonUpload->update_downloads;
        }

        $this->total_downloads = $webDownloads + $ingameDownloads + $updateDownloads;
        $this->web_downloads = $webDownloads;
        $this->ingame_downloads = $ingameDownloads;
        $this->update_downloads = $updateDownloads;
        $this->save();
    }
}
