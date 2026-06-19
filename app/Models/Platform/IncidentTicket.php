<?php

namespace App\Models\Platform;

use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentTicket extends Model
{
    protected $fillable = [
        'company_id',
        'reported_by_user_id',
        'fingerprint',
        'status',
        'title',
        'error_message',
        'context',
        'request_url',
        'http_method',
        'user_agent',
        'device_type',
        'ip_address',
        'ai_analysis',
        'ai_model',
        'ai_analyzed_at',
        'error_occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'error_occurred_at' => 'datetime',
            'ai_analyzed_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by_user_id');
    }
}
