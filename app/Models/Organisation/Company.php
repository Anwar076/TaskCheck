<?php

namespace App\Models\Organisation;

use App\Models\Billing\Invoice;
use App\Models\Submissions\SubmissionTask;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_type',
        'domain',
        'logo_path',
        'address',
        'phone',
        'email',
        'website',
        'description',
        'departments',
        'working_hours',
        'calendar_time_mode',
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
        'onboarding_step',
        'onboarding_list_mode',
        'onboarding_list_id',
        'onboarding_completed_at',
    ];

    public const ONBOARDING_STEP_WELCOME = 'welcome';
    public const ONBOARDING_STEP_ORGANIZATION = 'organization';
    public const ONBOARDING_STEP_USERS = 'users';
    public const ONBOARDING_STEP_STARTER_PACK = 'starter_pack';
    public const ONBOARDING_STEP_LIST_CHOICE = 'list_choice';
    public const ONBOARDING_STEP_LIST_CREATE = 'list_create';
    public const ONBOARDING_STEP_ASSIGN = 'assign';
    public const ONBOARDING_STEP_COMPLETED = 'completed';

    public const CALENDAR_TIME_MODE_WORKING_HOURS = 'working_hours';
    public const CALENDAR_TIME_MODE_24_HOURS = '24_hours';

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'onboarding_completed_at' => 'datetime',
        'billing_required' => 'boolean',
        'is_active' => 'boolean',
        'departments' => 'array',
        'working_hours' => 'array',
    ];

    public const WEEKDAYS = [
        'monday' => 'Maandag',
        'tuesday' => 'Dinsdag',
        'wednesday' => 'Woensdag',
        'thursday' => 'Donderdag',
        'friday' => 'Vrijdag',
        'saturday' => 'Zaterdag',
        'sunday' => 'Zondag',
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
        // Always set a concrete end date so hasActiveSubscription() has a hard boundary.
        // For recurring subscriptions this gets extended each billing cycle via the webhook.
        // Default is 1 month + 3-day grace period to absorb any Mollie processing delays.
        $endDate = now()->addMonths($months ?? 1)->addDays(3);

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
            return SubmissionTask::whereHas('submission', function ($q) {
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

    public static function defaultWorkingHours(): array
    {
        return collect(array_keys(self::WEEKDAYS))
            ->mapWithKeys(fn ($day) => [$day => [
                'enabled' => true,
                'start' => '06:00',
                'end' => '21:00',
            ]])
            ->all();
    }

    public function normalizedWorkingHours(): array
    {
        $configured = is_array($this->working_hours) ? $this->working_hours : [];

        return collect(self::defaultWorkingHours())
            ->mapWithKeys(function (array $defaults, string $day) use ($configured) {
                $dayConfig = is_array($configured[$day] ?? null) ? $configured[$day] : [];
                $enabled = array_key_exists('enabled', $dayConfig)
                    ? (bool) $dayConfig['enabled']
                    : (bool) $defaults['enabled'];
                $start = $this->normalizeWorkingHourTime($dayConfig['start'] ?? $defaults['start']);
                $end = $this->normalizeWorkingHourTime($dayConfig['end'] ?? $defaults['end']);

                if ($end <= $start) {
                    $start = $defaults['start'];
                    $end = $defaults['end'];
                }

                return [$day => [
                    'enabled' => $enabled,
                    'start' => $start,
                    'end' => $end,
                ]];
            })
            ->all();
    }

    public function workingHoursForDays(array $dayKeys): array
    {
        $hours = $this->normalizedWorkingHours();
        $selected = array_values(array_filter($dayKeys, fn ($day) => isset($hours[$day])));

        if ($selected === []) {
            $selected = array_keys(self::WEEKDAYS);
        }

        $enabled = array_values(array_filter(
            array_intersect_key($hours, array_flip($selected)),
            fn (array $day) => (bool) ($day['enabled'] ?? true)
        ));

        if ($enabled === []) {
            $enabled = array_values(array_intersect_key($hours, array_flip($selected)));
        }

        $startHour = min(array_map(fn (array $day) => (int) substr($day['start'], 0, 2), $enabled));
        $endHour = max(array_map(function (array $day) {
            [$hour, $minute] = array_map('intval', explode(':', $day['end']));

            return $minute > 0 ? $hour + 1 : $hour;
        }, $enabled));

        $startHour = max(0, min(23, $startHour));
        $endHour = max($startHour + 1, min(24, $endHour));

        return [
            'start_hour' => $startHour,
            'end_hour' => $endHour,
            'start_minutes' => $startHour * 60,
            'end_minutes' => $endHour * 60,
        ];
    }

    private function normalizeWorkingHourTime(mixed $value): string
    {
        $time = is_string($value) ? $value : '';

        if (! preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $time)) {
            return '06:00';
        }

        return $time;
    }

    public function hasCompletedOnboarding(): bool
    {
        return $this->onboarding_completed_at !== null;
    }

    public function needsOnboarding(): bool
    {
        return !$this->hasCompletedOnboarding();
    }

    public function advanceOnboardingTo(string $step): void
    {
        $this->update(['onboarding_step' => $step]);
    }

    public function completeOnboarding(): void
    {
        $this->update([
            'onboarding_step' => self::ONBOARDING_STEP_COMPLETED,
            'onboarding_completed_at' => now(),
            'onboarding_list_mode' => null,
            'onboarding_list_id' => null,
        ]);
    }

    public function onboardingRouteName(): string
    {
        return app(\App\Services\Platform\AdminOnboardingService::class)->redirectRoute($this);
    }

    /**
     * @return array<int, mixed>
     */
    public function onboardingRouteParameters(): array
    {
        return app(\App\Services\Platform\AdminOnboardingService::class)->redirectRouteParameters($this);
    }
}
