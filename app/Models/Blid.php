<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Blid extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'name',
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
     * Check if a BLID matches a given SteamID.
     */
    public static function auth(int $blid, int $steamId): array
    {
        $endpoint = 'http://master3.blockland.us/apiSteamIDBLID.php';

        $fields = [
            'blid' => $blid,
            'steamID' => $steamId,
        ];

        $postFields = '';

        foreach ($fields as $key => $value) {
            $postFields .= $key .= '='.$value.'&';
        }

        $postFields = rtrim($postFields, '&');

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Connection: keep-alive',
                'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: '.strlen($postFields),
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($ch);
            $parsedResult = explode(' ', trim($result));

            curl_close($ch);
        } catch (\Throwable $e) {
            Log::debug('Authentication FAILED: "'.$steamId.'" with BLID '.$blid.' ('.$e->getMessage().')');

            return ['success' => false, 'name' => null];
        }

        $success = $parsedResult[0] === 'YES';
        $name = $parsedResult[1] ?? null; // If a BLID does not have an in-game name set yet, this will be null.

        if ($success) {
            if ($name !== null) {
                Log::debug('Authentication SUCCESS: "'.$steamId.'" with BLID '.$blid.' ('.$name.')');
            } else {
                Log::debug('Authentication SUCCESS: "'.$steamId.'" with BLID '.$blid);
            }
        } else {
            Log::debug('Authentication FAILED: "'.$steamId.'" with BLID '.$blid);
        }

        return [
            'success' => $success,
            'name' => $name,
        ];
    }

    /**
     * Get the BLID's associated user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the BLID's add-ons.
     */
    public function addons(): HasMany
    {
        return $this->hasMany(Addon::class);
    }

    /**
     * Get the BLID's comments.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(AddonComment::class);
    }
}
