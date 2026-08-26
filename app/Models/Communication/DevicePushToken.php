<?php

namespace App\Models\Communication;

use App\Models\Organisation\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevicePushToken extends Model
{
    protected $fillable = [
        'user_id',
        'expo_push_token',
        'native_push_token',
        'push_provider',
        'platform',
        'device_name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
