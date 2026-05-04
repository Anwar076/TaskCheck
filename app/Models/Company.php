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
        'billing_required',
        'mollie_customer_id',
        'mollie_subscription_id',
        'mollie_payment_id',
        'max_users',
        'max_locations',
        'max_storage_gb',
        'is_active',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'billing_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Plan configurations
    const PLANS = [
        'starter' => [
            'name' => 'Starter',
            'price_monthly' => 39,
            'price_annual' => 31, // 20% discount
            'max_users' => 6, // 1 admin + 5 medewerkers
            'max_locations' => 1,
            'max_storage_gb' => 5,
        ],
        'professional' => [
            'name' => 'Professional',
            'price_monthly' => 99,
            'price_annual' => 79,
            'max_users' => 12, // 2 admins + 10 medewerkers
            'max_locations' => 2,
            'max_storage_gb' => 50,
        ],
        'business' => [
            'name' => 'Business',
            'price_monthly' => 179,
            'price_annual' => 143,
            'max_users' => 25, // 5 admins + 20 medewerkers
            'max_locations' => 3,
            'max_storage_gb' => -1,
        ],
        // Legacy paid enterprise plan (kept for backward compatibility).
        'enterprise' => [
            'name' => 'Enterprise (Legacy)',
            'price_monthly' => 149,
            'price_annual' => 119,
            'max_users' => 25,
            'max_locations' => -1,
            'max_storage_gb' => -1,
        ],
        'custom' => [
            'name' => 'Enterprise',
            'price_monthly' => 0,
            'price_annual' => 0,
            'max_users' => -1, // Unlimited
            'max_locations' => -1, // Unlimited
            'max_storage_gb' => -1, // Unlimited
        ],
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
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

    // Start trial period (14 days)
    public function startTrial(): void
    {
        $this->update([
            'subscription_status' => 'trial',
            'trial_ends_at' => now()->addDays(14),
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
            'max_locations' => self::PLANS[$plan]['max_locations'] ?? 1,
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

    public function hasReachedLocationLimit(): bool
    {
        $locationLimit = $this->getLocationLimit();
        if ($locationLimit === -1) {
            return false;
        }

        return $this->locations()->where('is_active', true)->count() >= $locationLimit;
    }

    public function getLocationCount(): int
    {
        return $this->locations()->where('is_active', true)->count();
    }

    public function getLocationLimit(): int
    {
        $plan = $this->subscription_plan;

        // Keep custom plan configurable through DB.
        if ($plan === 'custom') {
            return (int) ($this->max_locations ?? -1);
        }

        if ($plan && isset(self::PLANS[$plan]['max_locations'])) {
            return (int) self::PLANS[$plan]['max_locations'];
        }

        return (int) ($this->max_locations ?? 1);
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

