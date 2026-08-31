<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = ['plan_key', 'name', 'billing_period', 'billing_amount', 'trial_duration_value', 'trial_duration_unit', 'price_monthly', 'price_annual', 'max_users', 'max_locations', 'max_storage_gb', 'is_public', 'features'];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'price_annual' => 'decimal:2',
        'billing_amount' => 'decimal:2',
        'trial_duration_value' => 'integer',
        'max_users' => 'integer',
        'max_locations' => 'integer',
        'max_storage_gb' => 'integer',
        'is_public' => 'boolean',
        'features' => 'array',
    ];
}
