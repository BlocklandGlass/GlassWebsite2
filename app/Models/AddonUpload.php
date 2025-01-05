<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AddonUpload extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'addon_id',
        'file_name',
        'file_size',
        'file_path',
        'version',
        'restart_required',
        'changelog',
        'review_status',
        'review_comment',
        'reviewer_id',
        'reviewed_at',
        'total_downloads',
        'web_downloads',
        'ingame_downloads',
        'update_downloads',
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
        'restart_required' => 'boolean',
    ];

    /**
     * Get the add-on upload's associated add-on.
     */
    public function addon(): BelongsTo
    {
        return $this->belongsTo(Addon::class);
    }

    /**
     * Get the add-on upload's statistics.
     */
    public function addon_upload_statistics(): HasMany
    {
        return $this->hasMany(AddonUploadStatistic::class);
    }

    /**
     * Get the add-on upload's created_at in a human-readable format.
     */
    public function getHumanReadableCreatedAtAttribute(): string
    {
        return $this->created_at->format('M jS Y, g:i A');
    }

    /*
     * TODO: Write function description.
     */
    public function getMajorAttribute(): string
    {
        $version = explode('.', $this->version);

        return $version[0];
    }

    /*
     * TODO: Write function description.
     */
    public function getMinorAttribute(): string
    {
        $version = explode('.', $this->version);

        return $version[1];
    }

    /*
     * TODO: Write function description.
     */
    public function getPatchAttribute(): string
    {
        $version = explode('.', $this->version);

        return $version[2];
    }

    /*
     * TODO: Write function description.
     */
    public function addStatistic($type, $ipAddress): bool
    {
        if ($type !== 'web' && $type !== 'ingame' && $type !== 'update') {
            return false;
        }

        if ($ipAddress === '') {
            return false;
        }

        if ($ipAddress === '::1') {
            $ipAddress = '127.0.0.1';
        }

        $addonUploadStatistic = AddonUploadStatistic::where('addon_upload_id', $this->id)
            ->where('created_at', '>', Carbon::now()->subDays(1)->toDateTimeString())
            ->where('type', $type)
            ->where('ip_address', $ipAddress)
            ->exists();

        if (! $addonUploadStatistic) {
            $addonUploadStatistic = AddonUploadStatistic::create([
                'addon_upload_id' => $this->id,
                'type' => $type,
                'ip_address' => $ipAddress,
            ]);

            if (! $addonUploadStatistic) {
                return false;
            }
        }

        return true;
    }

    /*
     * TODO: Write function description.
     */
    public function updateStatistics(): void
    {
        $webDownloads = 0;
        $ingameDownloads = 0;
        $updateDownloads = 0;

        $addonUploadStatistics = $this->addon_upload_statistics;

        foreach ($addonUploadStatistics as $addonUploadStatistic) {
            if ($addonUploadStatistic->type === 'web') {
                $webDownloads++;
            } elseif ($addonUploadStatistic->type === 'ingame') {
                $ingameDownloads++;
            } elseif ($addonUploadStatistic->type === 'update') {
                $updateDownloads++;
            }
        }

        $this->total_downloads = $webDownloads + $ingameDownloads + $updateDownloads;
        $this->web_downloads = $webDownloads;
        $this->ingame_downloads = $ingameDownloads;
        $this->update_downloads = $updateDownloads;
        $this->save();
    }
}
