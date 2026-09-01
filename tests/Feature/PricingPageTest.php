<?php

namespace Tests\Feature;

use App\Models\Billing\SubscriptionPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PricingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pricing_page_uses_the_managed_subscription_price(): void
    {
        SubscriptionPlan::query()->create([
            'plan_key' => 'starter',
            'name' => 'Starter',
            'billing_period' => 'monthly',
            'billing_amount' => 49,
            'trial_duration_value' => 14,
            'trial_duration_unit' => 'days',
            'price_monthly' => 49,
            'price_annual' => 0,
            'max_users' => 6,
            'max_locations' => 1,
            'max_storage_gb' => 5,
            'is_public' => true,
            'features' => [],
        ]);

        $response = $this->get(route('pricing'));

        $response
            ->assertOk()
            ->assertSee('€49', false)
            ->assertDontSee('€39', false)
            ->assertSee('<link rel="canonical" href="'.route('pricing').'">', false)
            ->assertSee('TaskCheck prijzen en abonnementen | 14 dagen gratis')
            ->assertSee('images/taskcheck-pricing-social.png', false)
            ->assertSee('<script type="application/ld+json">', false)
            ->assertSee('"price":"49.00"', false)
            ->assertSee('enterprise-grid', false)
            ->assertSee('primary-action', false)
            ->assertSeeText('Na je proefperiode ga je naar een beveiligde Mollie-checkout.')
            ->assertDontSeeText('Welk abonnement past bij mij?');

        $this->assertSame(1, substr_count($response->getContent(), '<meta name="description"'));
    }
}
