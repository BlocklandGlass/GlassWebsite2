<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'steam_id',
        'primary_blid',
        'name',
        'avatar_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     * Get the user's primary BLID.
     */
    public function primary_blid(): BelongsTo
    {
        return $this->belongsTo(Blid::class, 'primary_blid');
    }

    /**
     * Get the user's associated BLIDs.
     */
    public function blids(): HasMany
    {
        return $this->hasMany(Blid::class);
    }

    /**
     * Get the user's primary BLID name if available.
     */
    public function getNameAttribute(string $value): string
    {
        return $this->primary_blid()?->first()->name ?? 'Unverified User';
    }
}
