<?php

namespace App\Services\Billing;

use App\Models\Organisation\Company;

class RecurringSubscriptionService
{
    public function extendPaidAccess(Company $company): void
    {
        if (! $company->hasActiveSubscription()) {
            return;
        }

        $base = $company->subscription_ends_at?->isFuture() ? $company->subscription_ends_at->copy() : now();
        $months = match ($company->billing_period ?: (Company::plan($company->subscription_plan)['billing_period'] ?? 'monthly')) {
            'quarterly' => 3,
            'semiannual' => 6,
            'annual' => 12,
            default => 1,
        };

        $company->update(['subscription_ends_at' => $base->addMonths($months)->addDays(3)]);
    }
}
