<?php

namespace Tests\Feature;

use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use App\Models\Billing\SubscriptionPlan;
use App\Services\Billing\MollieService;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class SubscriptionFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_activation_always_creates_monthly_interval_for_starter_plan(): void
    {
        Config::set('services.mollie.webhook_url', 'https://example.test/mollie/webhook');

        $company = Company::query()->create([
            'name' => 'Test Company',
            'subscription_status' => 'trial',
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'role' => 'admin',
            'email' => 'billing@example.com',
            'company_id' => $company->id,
        ]);

        $capturedPayload = null;

        $mollie = Mockery::mock(MollieService::class);
        $mollie->shouldReceive('createCustomer')
            ->once()
            ->andReturnUsing(fn () => ['id' => 'cst_test_1']);
        $mollie->shouldReceive('createFirstPayment')
            ->once()
            ->andReturnUsing(function (array $payload) use (&$capturedPayload) {
                $capturedPayload = $payload;

                return [
                    'id' => 'tr_test_1',
                    '_links' => [
                        'checkout' => [
                            'href' => 'https://checkout.mollie.com/payments/test',
                        ],
                    ],
                ];
            });

        $this->instance(MollieService::class, $mollie);

        $csrf = 'test-csrf-token';
        $response = $this
            ->actingAs($user)
            ->withSession(['_token' => $csrf])
            ->post(route('subscription.activate'), [
                '_token' => $csrf,
                'plan' => 'starter',
            ]);

        $response->assertRedirect('https://checkout.mollie.com/payments/test');

        $this->assertNotNull($capturedPayload);
        $this->assertSame('1 month', data_get($capturedPayload, 'metadata.interval'));
        $this->assertSame('47.19', data_get($capturedPayload, 'amount.value'));
    }

    public function test_annual_plan_uses_full_amount_and_twelve_month_mollie_interval(): void
    {
        Config::set('services.mollie.webhook_url', 'https://example.test/mollie/webhook');
        $defaults = Company::PLANS['professional'];
        SubscriptionPlan::query()->create(array_merge($defaults, [
            'plan_key' => 'professional',
            'billing_period' => 'annual',
            'billing_amount' => 950,
            'features' => [],
        ]));
        $company = Company::query()->create(['name' => 'Annual Company', 'subscription_status' => 'trial', 'is_active' => true]);
        $user = User::factory()->create(['role' => 'admin', 'email' => 'annual@example.com', 'company_id' => $company->id]);
        $capturedPayload = null;
        $mollie = Mockery::mock(MollieService::class);
        $mollie->shouldReceive('createCustomer')->once()->andReturn(['id' => 'cst_annual']);
        $mollie->shouldReceive('createFirstPayment')->once()->andReturnUsing(function (array $payload) use (&$capturedPayload) {
            $capturedPayload = $payload;
            return ['id' => 'tr_annual', '_links' => ['checkout' => ['href' => 'https://checkout.mollie.com/payments/annual']]];
        });
        $this->instance(MollieService::class, $mollie);

        $this->actingAs($user)->post(route('subscription.activate'), ['plan' => 'professional'])
            ->assertRedirect('https://checkout.mollie.com/payments/annual');

        $this->assertSame('12 months', data_get($capturedPayload, 'metadata.interval'));
        $this->assertSame('1149.50', data_get($capturedPayload, 'amount.value'));
    }

    public function test_paid_webhook_does_not_reactivate_company_after_full_cancellation(): void
    {
        $company = Company::query()->create([
            'name' => 'Cancelled Company',
            'subscription_plan' => 'starter',
            'subscription_status' => 'cancelled',
            'pending_subscription_plan' => null,
            'mollie_payment_id' => null,
            'is_active' => true,
        ]);

        $mollie = Mockery::mock(MollieService::class);
        $mollie->shouldReceive('getPayment')
            ->once()
            ->with('tr_paid_late')
            ->andReturnUsing(fn () => [
                'id' => 'tr_paid_late',
                'status' => 'paid',
                'sequenceType' => 'first',
                'amount' => ['currency' => 'EUR', 'value' => '47.19'],
                'metadata' => ['company_id' => $company->id, 'plan' => 'starter', 'interval' => '1 month'],
            ]);

        $this->instance(MollieService::class, $mollie);

        $this->post(route('subscription.mollie.webhook'), ['id' => 'tr_paid_late'])
            ->assertOk();

        $company->refresh();

        $this->assertSame('cancelled', $company->subscription_status);
        $this->assertSame('starter', $company->subscription_plan);
        $this->assertNull($company->mollie_payment_id);
        $this->assertNull($company->pending_subscription_plan);
    }
}
