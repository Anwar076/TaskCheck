<?php

namespace App\Models\Organisation;

use App\Models\Billing\Invoice;
use App\Models\Billing\SubscriptionPlan;
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
        'reporting_enabled',
        'reporting_frequency',
        'reporting_send_time',
        'reporting_weekly_day',
        'reporting_last_sent_at',
        'subscription_plan',
        'custom_subscription_name',
        'custom_monthly_price',
        'pending_subscription_plan',
        'subscription_status',
        'trial_ends_at',
        'subscription_ends_at',
        'billing_required',
        'billing_period',
        'billing_start_date',
        'signup_source',
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
        'trial_expired_email_sent_at',
        'payment_invitation_sent_at',
        'entra_enabled',
        'entra_sso_required',
        'entra_mfa_required',
        'entra_tenant_id',
        'entra_client_id',
        'entra_client_secret',
        'entra_admin_group_ids',
        'entra_employee_group_ids',
        'scim_endpoint_key',
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
    public const REPORTING_FREQUENCY_DAILY = 'daily';
    public const REPORTING_FREQUENCY_WEEKLY = 'weekly';
    public const SIGNUP_SOURCE_SELF_SERVICE = 'self_service';
    public const SIGNUP_SOURCE_MANAGED = 'managed';

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'onboarding_completed_at' => 'datetime',
        'billing_required' => 'boolean',
        'billing_start_date' => 'date',
        'custom_monthly_price' => 'decimal:2',
        'is_active' => 'boolean',
        'departments' => 'array',
        'working_hours' => 'array',
        'reporting_enabled' => 'boolean',
        'reporting_last_sent_at' => 'datetime',
        'trial_expired_email_sent_at' => 'datetime',
        'payment_invitation_sent_at' => 'datetime',
        'entra_enabled' => 'boolean',
        'entra_sso_required' => 'boolean',
        'entra_mfa_required' => 'boolean',
        'entra_client_secret' => 'encrypted',
        'entra_admin_group_ids' => 'array',
        'entra_employee_group_ids' => 'array',
    ];

    public function isManagedAccount(): bool
    {
        return $this->signup_source !== self::SIGNUP_SOURCE_SELF_SERVICE;
    }

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
            'max_locations' => 1,
            'max_storage_gb' => 50,
        ],
        'business' => [
            'name' => 'Business',
            'price_monthly' => 179,
            'price_annual' => 143,
            'max_users' => 23, // 3 admins + 20 medewerkers
            'max_locations' => 2,
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
            'name' => 'Maatwerk',
            'price_monthly' => 0,
            'price_annual' => 0,
            'max_users' => -1, // Unlimited
            'max_locations' => -1, // Unlimited
            'max_storage_gb' => -1, // Unlimited
        ],
    ];

    public const PLAN_ROLE_LIMITS = [
        'starter' => ['admin' => 1, 'employee' => 5],
        'professional' => ['admin' => 2, 'employee' => 10],
        'business' => ['admin' => 3, 'employee' => 20],
        'enterprise' => ['admin' => 3, 'employee' => 20],
        'custom' => ['admin' => null, 'employee' => null],
    ];

    public const PLAN_FEATURES = [
        'ai_import' => 'AI-import voor documenten',
        'ai_suggestions' => 'AI-suggesties voor taken',
        'reports' => 'Weekoverzicht en rapportages',
    ];

    public const CORE_FEATURES = [
        'Takenlijsten en checklists',
        'Foto- en videobewijs',
        'Realtime voortgangsoverzicht',
        'Mobiele webapp voor medewerkers',
    ];

    public const BILLING_PERIODS = [
        'monthly' => ['label' => 'Maandelijks', 'suffix' => 'maand', 'mollie_interval' => '1 month'],
        'quarterly' => ['label' => 'Per kwartaal', 'suffix' => 'kwartaal', 'mollie_interval' => '3 months'],
        'semiannual' => ['label' => 'Halfjaarlijks', 'suffix' => 'halfjaar', 'mollie_interval' => '6 months'],
        'annual' => ['label' => 'Jaarlijks', 'suffix' => 'jaar', 'mollie_interval' => '12 months'],
    ];

    public static function plans(): array
    {
        $plans = self::PLANS;

        if (! \Illuminate\Support\Facades\Schema::hasTable('subscription_plans')) {
            return $plans;
        }

        foreach (SubscriptionPlan::query()->get() as $override) {
            $plans[$override->plan_key] = [
                'name' => $override->name,
                'billing_period' => $override->billing_period,
                'billing_amount' => (float) ($override->billing_amount ?? ($override->billing_period === 'annual' ? $override->price_annual : $override->price_monthly)),
                'trial_duration_value' => (int) $override->trial_duration_value,
                'trial_duration_unit' => $override->trial_duration_unit,
                'price_monthly' => (float) $override->price_monthly,
                'price_annual' => (float) $override->price_annual,
                'max_users' => $override->max_users,
                'max_locations' => $override->max_locations,
                'max_storage_gb' => $override->max_storage_gb,
                'features' => $override->features ?? self::defaultPlanFeatures($override->plan_key),
            ];
        }

        foreach ($plans as $key => &$plan) {
            $plan['billing_period'] ??= 'monthly';
            $plan['billing_amount'] ??= (float) $plan['price_monthly'];
            $plan['features'] ??= self::defaultPlanFeatures($key);
            $plan['trial_duration_value'] ??= 14;
            $plan['trial_duration_unit'] ??= 'days';
        }
        unset($plan);

        return $plans;
    }

    public static function publicPlans(): array
    {
        $publicKeys = ['starter', 'professional', 'business'];

        return array_intersect_key(self::plans(), array_flip($publicKeys));
    }

    public static function defaultPlanFeatures(string $key): array
    {
        return $key === 'starter' ? [] : array_keys(self::PLAN_FEATURES);
    }

    public function hasPlanFeature(string $feature): bool
    {
        return in_array($feature, $this->getPlanDetails()['features'] ?? [], true);
    }

    public static function billingPeriod(string $key): array
    {
        return self::BILLING_PERIODS[$key] ?? self::BILLING_PERIODS['monthly'];
    }

    public static function trialEndForPlan(?string $planKey, ?Carbon $start = null): Carbon
    {
        $plan = self::plan($planKey ?: 'starter') ?? [];
        $value = max(1, (int) ($plan['trial_duration_value'] ?? 14));
        $date = ($start ?: now())->copy();

        return match ($plan['trial_duration_unit'] ?? 'days') {
            'weeks' => $date->addWeeks($value),
            'months' => $date->addMonthsNoOverflow($value),
            default => $date->addDays($value),
        };
    }

    public static function plan(string $key): ?array
    {
        return self::plans()[$key] ?? null;
    }

    /**
     * @return array{admin: int|null, employee: int|null}
     */
    public static function planRoleLimits(?string $planKey): array
    {
        $key = $planKey ?: 'starter';

        if (! isset(self::PLAN_ROLE_LIMITS[$key]) && self::plan($key)) {
            return ['admin' => null, 'employee' => null];
        }

        return self::PLAN_ROLE_LIMITS[$key] ?? self::PLAN_ROLE_LIMITS['starter'];
    }

    /**
     * @return array<int, string>
     */
    public static function planMarketingFeatures(string $planKey): array
    {
        return match ($planKey) {
            'starter' => [
                '1 admin account',
                '5 medewerker accounts',
                '1 locatie',
                'Taken met foto- en videobewijs',
                'Realtime voortgangsoverzicht',
                'Mobiele webapp (installeerbaar)',
            ],
            'professional' => [
                '2 admin accounts',
                '10 medewerker accounts',
                '1 locatie',
                'AI-import (PDF, Excel, Word of foto)',
                'Weekoverzicht & rapportages',
                'Taken met foto- en videobewijs',
                'Realtime voortgangsoverzicht',
                'Mobiele webapp (installeerbaar)',
                'Priority support',
            ],
            'business' => [
                '3 admin accounts',
                '20 medewerker accounts',
                '2 locaties',
                'Uitgebreide rapportages per locatie',
                'Inzicht in prestaties per team en locatie',
                'Taken met foto- en videobewijs',
                'Realtime voortgangsoverzicht',
                'Mobiele webapp (installeerbaar)',
                'Priority support',
            ],
            default => [],
        };
    }

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

    public function reportRecipients()
    {
        return $this->hasMany(CompanyReportRecipient::class);
    }

    // Check if company is on trial
    public function isOnTrial(): bool
    {
        return (bool) ($this->trial_ends_at?->isFuture()
            && ($this->subscription_status === 'trial' || $this->isAwaitingFirstPayment()));
    }

    // Check if trial has expired
    public function trialExpired(): bool
    {
        return (bool) ($this->trial_ends_at?->isPast()
            && ($this->subscription_status === 'trial' || $this->isAwaitingFirstPayment()));
    }

    // Check if subscription is active
    public function hasActiveSubscription(): bool
    {
        return $this->subscription_status === 'active' 
            && !$this->isAwaitingFirstPayment()
            && (!$this->subscription_ends_at || $this->subscription_ends_at->isFuture());
    }

    /**
     * A paid plan with a configured trial is not active until the first Mollie
     * payment has established a subscription (or access was explicitly granted
     * through an end date).
     */
    public function isAwaitingFirstPayment(): bool
    {
        return (bool) ($this->billing_required
            && in_array($this->subscription_status, ['trial', 'active'], true)
            && $this->trial_ends_at
            && !$this->mollie_subscription_id
            && !$this->subscription_ends_at);
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

    public function accessLockMessage(): ?string
    {
        if ($this->canAccess()) {
            return null;
        }

        if (! $this->is_active) {
            return 'Dit account is gedeactiveerd. Neem contact op met support.';
        }

        if ($this->trialExpired()) {
            return 'Je proefperiode is verlopen. Kies een abonnement om TaskCheck weer te gebruiken.';
        }

        if ($this->subscription_status === 'cancelled') {
            return 'Je abonnement is opgezegd. Kies een abonnement om TaskCheck weer te gebruiken.';
        }

        if ($this->subscription_status === 'expired') {
            return 'Je abonnement is verlopen. Kies een abonnement om TaskCheck weer te gebruiken.';
        }

        return 'Je hebt geen actief abonnement. Kies een abonnement om TaskCheck weer te gebruiken.';
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
        $trialEnd = self::trialEndForPlan($this->subscription_plan);
        $this->update([
            'subscription_status' => 'trial',
            'trial_ends_at' => $trialEnd,
            'billing_period' => 'monthly',
            'billing_start_date' => $trialEnd->toDateString(),
            'trial_expired_email_sent_at' => null,
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
            'max_users' => self::plan($plan)['max_users'] ?? 5,
            'max_locations' => self::plan($plan)['max_locations'] ?? 1,
            'max_storage_gb' => self::plan($plan)['max_storage_gb'] ?? 5,
        ]);
    }

    // Get plan details
    public function getPlanDetails(): array
    {
        if (!$this->subscription_plan) {
            return [];
        }

        $details = self::plan($this->subscription_plan) ?? [];
        $details['billing_period'] = $this->billing_period ?: ($details['billing_period'] ?? 'monthly');

        if ($this->subscription_plan === 'custom') {
            $details['name'] = $this->custom_subscription_name ?: $details['name'];
            $details['price_monthly'] = (float) ($this->custom_monthly_price ?? 0);
            $details['billing_period'] = $this->billing_period ?: 'monthly';
            $details['billing_amount'] = (float) ($this->custom_monthly_price ?? 0);
            $details['max_users'] = (int) $this->max_users;
            $details['max_locations'] = (int) $this->max_locations;
            $details['max_storage_gb'] = (int) $this->max_storage_gb;
        }

        return $details;
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

        if ($plan && ($planDetails = self::plan($plan))) {
            return (int) $planDetails['max_locations'];
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
