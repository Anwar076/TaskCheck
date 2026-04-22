<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'logo_path',
        'address',
        'phone',
        'email',
        'website',
        'description',
        'subscription_plan',
        'pending_subscription_plan',
        'subscription_status',
        'trial_ends_at',
        'subscription_ends_at',
        'mollie_customer_id',
        'mollie_subscription_id',
        'mollie_payment_id',
        'max_users',
        'max_storage_gb',
        'is_active',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Plan configurations
    const PLANS = [
        'starter' => [
            'name' => 'Starter',
            'price_monthly' => 29,
            'price_annual' => 23, // 20% discount
            'max_users' => 6, // 1 admin + 5 medewerkers
            'max_storage_gb' => 5,
        ],
        'professional' => [
            'name' => 'Professional',
            'price_monthly' => 79,
            'price_annual' => 63,
            'max_users' => 12, // 2 admins + 10 medewerkers
            'max_storage_gb' => 50,
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'price_monthly' => 149,
            'price_annual' => 119,
            'max_users' => 25, // 5 admins + 20 medewerkers
            'max_storage_gb' => -1, // Unlimited
        ],
        'custom' => [
            'name' => 'Custom',
            'price_monthly' => 0,
            'price_annual' => 0,
            'max_users' => -1, // Unlimited
            'max_storage_gb' => -1, // Unlimited
        ],
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Check if company is on trial
    public function isOnTrial(): bool
    {
        return $this->subscription_status === 'trial' 
            && $this->trial_ends_at 
            && $this->trial_ends_at->isFuture();
    }

    // Check if trial has expired
    public function trialExpired(): bool
    {
        return $this->subscription_status === 'trial' 
            && $this->trial_ends_at 
            && $this->trial_ends_at->isPast();
    }

    // Check if subscription is active
    public function hasActiveSubscription(): bool
    {
        return $this->subscription_status === 'active' 
            && (!$this->subscription_ends_at || $this->subscription_ends_at->isFuture());
    }

    public function hasCancelledButStillActiveAccess(): bool
    {
        return $this->subscription_status === 'cancelled'
            && $this->subscription_ends_at
            && $this->subscription_ends_at->isFuture();
    }

    // Check if company can access the system
    public function canAccess(): bool
    {
        return $this->is_active && (
            $this->isOnTrial()
            || $this->hasActiveSubscription()
            || $this->hasCancelledButStillActiveAccess()
        );
    }

    // Get days remaining in trial
    public function trialDaysRemaining(): int
    {
        if (!$this->isOnTrial()) {
            return 0;
        }

        return max(0, now()->diffInDays($this->trial_ends_at, false));
    }

    // Start trial period (30 days)
    public function startTrial(): void
    {
        $this->update([
            'subscription_status' => 'trial',
            'trial_ends_at' => now()->addDays(30),
        ]);
    }

    // Activate subscription
    public function activateSubscription(string $plan, ?int $months = null): void
    {
        $endDate = $months ? now()->addMonths($months) : null;
        
        $this->update([
            'subscription_plan' => $plan,
            'subscription_status' => 'active',
            'subscription_ends_at' => $endDate,
            'max_users' => self::PLANS[$plan]['max_users'] ?? 5,
            'max_storage_gb' => self::PLANS[$plan]['max_storage_gb'] ?? 5,
        ]);
    }

    // Get plan details
    public function getPlanDetails(): array
    {
        if (!$this->subscription_plan) {
            return [];
        }

        return self::PLANS[$this->subscription_plan] ?? [];
    }

    // Check if user limit is reached
    public function hasReachedUserLimit(): bool
    {
        if ($this->max_users === -1) {
            return false; // Unlimited
        }

        return $this->users()->count() >= $this->max_users;
    }

    // Get current user count
    public function getUserCount(): int
    {
        return $this->users()->count();
    }

    /**
     * Get total storage used in bytes (from proof files in submissions)
     * Cached for 5 minutes to avoid heavy queries on each page load.
     */
    public function getStorageUsedBytes(): int
    {
        $cacheKey = "company_{$this->id}_storage_used_bytes";

        return (int) \Illuminate\Support\Facades\Cache::remember($cacheKey, 300, function () {
            return \App\Models\SubmissionTask::whereHas('submission', function ($q) {
                $q->where('company_id', $this->id);
            })->get()
                ->sum(function ($st) {
                    if (!$st->proof_files || !is_array($st->proof_files)) {
                        return 0;
                    }
                    return collect($st->proof_files)->sum('size');
                });
        });
    }

    /**
     * Get storage used in GB (rounded to 2 decimals)
     */
    public function getStorageUsedGb(): float
    {
        return round($this->getStorageUsedBytes() / (1024 ** 3), 2);
    }

    /**
     * Clear storage cache (call when new files are uploaded)
     */
    public function clearStorageCache(): void
    {
        \Illuminate\Support\Facades\Cache::forget("company_{$this->id}_storage_used_bytes");
    }
}

