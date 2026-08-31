<?php

namespace App\Models\Organisation;

use Illuminate\Database\Eloquent\Model;

class CompanyReportRecipient extends Model
{
    protected $fillable = ['email', 'frequency', 'send_time', 'weekly_day', 'delivery_format', 'is_enabled', 'last_sent_at'];

    protected $casts = ['is_enabled' => 'boolean', 'last_sent_at' => 'datetime'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
