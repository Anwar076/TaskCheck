<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformAlertLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'alert_key',
        'metric_value',
        'threshold',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
