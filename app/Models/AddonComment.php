<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddonComment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'addon_id',
        'blid_id',
        'body',
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
     * Get the add-on comment's associated add-on.
     */
    public function addon(): BelongsTo
    {
        return $this->belongsTo(Addon::class);
    }

    /**
     * Get the add-on comment's associated BLID.
     */
    public function blid(): BelongsTo
    {
        return $this->belongsTo(Blid::class);
    }

    /**
     * Get the add-on comment's created_at in a human-readable format.
     */
    public function getHumanReadableCreatedAtAttribute(): string
    {
        return $this->created_at->format('M jS Y, g:i A');
    }
}
