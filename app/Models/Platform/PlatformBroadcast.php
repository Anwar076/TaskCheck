<?php

namespace App\Models\Platform;

use App\Models\Organisation\User;
use Illuminate\Database\Eloquent\Model;

class PlatformBroadcast extends Model
{
    protected $fillable = ['sent_by', 'channel', 'subject', 'title', 'message', 'audience', 'recipients_count', 'failed_count', 'status', 'meta', 'sent_at'];

    protected function casts(): array
    {
        return ['meta' => 'array', 'sent_at' => 'datetime'];
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
