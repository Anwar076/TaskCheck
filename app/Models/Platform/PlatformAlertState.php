<?php

namespace App\Models\Platform;

use Illuminate\Database\Eloquent\Model;

class PlatformAlertState extends Model
{
    protected $primaryKey = 'alert_key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = ['opened_at' => 'datetime', 'healthy_since' => 'datetime'];
}
