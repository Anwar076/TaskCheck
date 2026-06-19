<?php

namespace App\Models\Marketing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingLinkClick extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'marketing_link_campaign_id',
        'visitor_hash',
        'user_agent',
        'referer',
        'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(MarketingLinkCampaign::class, 'marketing_link_campaign_id');
    }
}
