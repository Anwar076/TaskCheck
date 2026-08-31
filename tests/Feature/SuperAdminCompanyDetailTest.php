<?php

namespace Tests\Feature;

use App\Models\Checklist\TaskList;
use App\Models\Organisation\Location;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SuperAdminCompanyDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_super_admin_can_open_company_detail_page(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        $company = Company::firstOrFail();

        $response = $this->actingAs($admin)->get(route('super-admin.companies.show', $company));

        $response->assertOk()
            ->assertViewIs('super-admin.companies.show')
            ->assertSee($company->name)
            ->assertSee('Abonnement beheren')
            ->assertSee('Recente gebruikers');
    }

    public function test_super_admin_can_delete_a_company_with_its_users(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        $company = Company::query()->create([
            'name' => 'Verwijderbare klant',
            'subscription_plan' => 'starter',
            'subscription_status' => 'active',
            'is_active' => true,
        ]);
        $users = User::factory()->count(2)->create(['company_id' => $company->id]);
        Location::query()->create(['company_id' => $company->id, 'name' => 'Testlocatie']);

        $this->actingAs($admin)->delete(route('super-admin.companies.destroy', $company), [
            'confirmation_name' => 'Verwijderbare klant',
        ])->assertRedirect(route('super-admin.dashboard', ['tab' => 'companies']));

        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
        foreach ($users as $user) {
            $this->assertDatabaseMissing('users', ['id' => $user->id]);
        }
        $this->assertDatabaseMissing('locations', ['company_id' => $company->id]);
    }

    public function test_company_deletion_requires_the_exact_company_name(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        $company = Company::query()->create([
            'name' => 'Blijvende klant',
            'subscription_plan' => 'starter',
            'subscription_status' => 'active',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->from(route('super-admin.companies.show', $company))
            ->delete(route('super-admin.companies.destroy', $company), ['confirmation_name' => 'verkeerd'])
            ->assertSessionHasErrors('confirmation_name');

        $this->assertDatabaseHas('companies', ['id' => $company->id]);
    }

    public function test_super_admin_cannot_delete_their_own_company(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        $company = $admin->company;

        $this->actingAs($admin)->delete(route('super-admin.companies.destroy', $company), [
            'confirmation_name' => $company->name,
        ])->assertSessionHas('error');

        $this->assertDatabaseHas('companies', ['id' => $company->id]);
    }

    public function test_super_admin_sees_the_real_next_mollie_billing_date(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        config()->set('services.mollie.key', 'test_key');
        $company = Company::firstOrFail();
        $company->update(['mollie_customer_id' => 'cst_test', 'mollie_subscription_id' => 'sub_test']);
        Http::fake([
            'https://api.mollie.com/v2/customers/cst_test/subscriptions/sub_test' => Http::response([
                'status' => 'active',
                'nextPaymentDate' => '2026-09-15',
                'interval' => '1 month',
                'amount' => ['value' => '99.00', 'currency' => 'EUR'],
            ]),
        ]);

        $this->actingAs($admin)
            ->get(route('super-admin.companies.show', ['company' => $company, 'section' => 'billing']))
            ->assertOk()
            ->assertSee('Volgende facturatie')
            ->assertSee('15-09-2026')
            ->assertSee('Maandelijks')
            ->assertSee('EUR 99,00');
    }

    public function test_super_admin_can_change_billing_schedule_and_it_updates_mollie(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        config()->set('services.mollie.key', 'test_key');
        $company = Company::firstOrFail();
        $company->update(['mollie_customer_id' => 'cst_schedule', 'mollie_subscription_id' => 'sub_schedule']);
        Http::fake(['https://api.mollie.com/v2/customers/cst_schedule/subscriptions/sub_schedule' => Http::response(['id' => 'sub_schedule'])]);

        $this->actingAs($admin)->put(route('super-admin.companies.subscription.update', $company), [
            'subscription_plan' => 'starter',
            'subscription_status' => 'active',
            'billing_required' => '1',
            'billing_period' => 'quarterly',
            'billing_start_date' => now()->addMonth()->toDateString(),
            'trial_ends_at' => now()->addWeeks(3)->toDateString(),
            'is_active' => '1',
        ])->assertRedirect(route('super-admin.companies.show', ['company' => $company, 'section' => 'billing']));

        $company->refresh();
        $this->assertSame('quarterly', $company->billing_period);
        $this->assertSame(now()->addMonth()->toDateString(), $company->billing_start_date?->toDateString());
        Http::assertSent(fn ($request) => $request->method() === 'PATCH'
            && data_get($request->data(), 'interval') === '3 months'
            && data_get($request->data(), 'startDate') === now()->addMonth()->toDateString());
    }

    public function test_trial_length_and_default_first_billing_date_are_visible(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        $company = Company::firstOrFail();
        $company->update([
            'created_at' => now()->startOfDay(),
            'trial_ends_at' => now()->addDays(14)->startOfDay(),
            'billing_start_date' => now()->addDays(14)->toDateString(),
            'billing_period' => 'monthly',
        ]);

        $this->actingAs($admin)
            ->get(route('super-admin.companies.show', ['company' => $company, 'section' => 'billing']))
            ->assertOk()
            ->assertSee('14 dagen')
            ->assertSee(now()->addDays(14)->format('d-m-Y'))
            ->assertSee('Geplande eerste facturatie');
    }

    public function test_super_admin_can_open_global_users_overview(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);

        $this->actingAs($admin)
            ->get(route('super-admin.dashboard', ['tab' => 'users']))
            ->assertOk()
            ->assertViewIs('super-admin.dashboard')
            ->assertViewHas('activeDashboardTab', 'users')
            ->assertSee('Gebruikersoverzicht')
            ->assertSee($admin->email);
    }

    public function test_super_admin_can_open_company_creation_page(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);

        $this->actingAs($admin)
            ->get(route('super-admin.companies.create'))
            ->assertOk()
            ->assertViewIs('super-admin.companies.create')
            ->assertSee('Bedrijf en beheerder aanmaken');
    }

    public function test_super_admin_can_open_subscription_overview_and_detail(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);

        $this->actingAs($admin)
            ->get(route('super-admin.subscriptions.index'))
            ->assertOk()
            ->assertSee('Abonnementen')
            ->assertSee('Starter')
            ->assertSee('Maatwerk');

        $this->actingAs($admin)
            ->get(route('super-admin.subscriptions.show', 'starter'))
            ->assertOk()
            ->assertSee('Gekoppelde klanten');

        $this->actingAs($admin)
            ->get(route('super-admin.subscriptions.show', 'bestaat-niet'))
            ->assertNotFound();
    }

    public function test_super_admin_can_edit_a_subscription_plan(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);

        $this->actingAs($admin)->put(route('super-admin.subscriptions.update', 'professional'), [
            'name' => 'Professional Plus',
            'billing_period' => 'monthly',
            'price' => '109.00',
            'trial_duration_value' => 2,
            'trial_duration_unit' => 'weeks',
            'max_users' => 15,
            'max_locations' => 3,
            'max_storage_gb' => 75,
            'features' => ['ai_import', 'reports'],
        ])->assertRedirect(route('super-admin.subscriptions.show', 'professional'));

        $this->assertDatabaseHas('subscription_plans', [
            'plan_key' => 'professional',
            'name' => 'Professional Plus',
            'max_users' => 15,
        ]);
        $this->assertSame('Professional Plus', Company::plan('professional')['name']);
        $this->assertSame(['ai_import', 'reports'], Company::plan('professional')['features']);
    }

    public function test_super_admin_can_create_a_subscription_plan(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);

        $this->actingAs($admin)
            ->get(route('super-admin.subscriptions.create'))
            ->assertOk()
            ->assertSee('Nieuw abonnement aanmaken')
            ->assertSee('Vaste projectvereisten')
            ->assertSee('Capaciteitsvereisten')
            ->assertSee('Optionele onderdelen');

        $response = $this->actingAs($admin)->post(route('super-admin.subscriptions.store'), [
            'name' => 'Franchise Plus',
            'billing_period' => 'annual',
            'price' => '2290.00',
            'trial_duration_value' => 1,
            'trial_duration_unit' => 'months',
            'max_users' => 40,
            'max_locations' => 12,
            'max_storage_gb' => 200,
            'features' => ['ai_import', 'ai_suggestions'],
        ]);

        $response->assertRedirect(route('super-admin.subscriptions.show', 'franchise_plus'));
        $this->assertDatabaseHas('subscription_plans', [
            'plan_key' => 'franchise_plus',
            'name' => 'Franchise Plus',
            'is_public' => false,
        ]);
        $this->assertSame(40, Company::plan('franchise_plus')['max_users']);
        $this->assertSame('annual', Company::plan('franchise_plus')['billing_period']);
        $this->assertSame(1, Company::plan('franchise_plus')['trial_duration_value']);
        $this->assertSame('months', Company::plan('franchise_plus')['trial_duration_unit']);
        $this->assertSame(2290.0, Company::plan('franchise_plus')['billing_amount']);
        $this->assertSame(0.0, Company::plan('franchise_plus')['price_monthly']);
        $this->assertSame(2290.0, Company::plan('franchise_plus')['price_annual']);
        $this->assertSame(['ai_import', 'ai_suggestions'], Company::plan('franchise_plus')['features']);
        $this->assertSame(['admin' => null, 'employee' => null], Company::planRoleLimits('franchise_plus'));
    }

    public function test_subscription_end_date_is_saved_when_monthly_billing_is_enabled(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        $company = Company::firstOrFail();

        $this->actingAs($admin)->put(route('super-admin.companies.subscription.update', $company), [
            'subscription_plan' => 'starter',
            'subscription_status' => 'active',
            'billing_period' => 'monthly',
            'subscription_ends_at' => '2027-12-31',
            'billing_required' => '1',
            'is_active' => '1',
        ])->assertRedirect(route('super-admin.companies.show', ['company' => $company, 'section' => 'billing']));

        $this->assertSame('2027-12-31', $company->fresh()->subscription_ends_at?->format('Y-m-d'));
    }

    public function test_super_admin_can_save_a_customer_specific_subscription(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        $company = Company::firstOrFail();

        $this->actingAs($admin)->put(route('super-admin.companies.subscription.update', $company), [
            'subscription_plan' => 'custom',
            'subscription_status' => 'active',
            'billing_period' => 'monthly',
            'billing_required' => '1',
            'is_active' => '1',
            'custom_subscription_name' => 'Kwalitaria Plus',
            'custom_monthly_price' => '249.50',
            'custom_max_users' => '35',
            'custom_max_locations' => '8',
            'custom_max_storage_gb' => '100',
        ])->assertRedirect(route('super-admin.companies.show', ['company' => $company, 'section' => 'billing']));

        $company->refresh();
        $this->assertSame('custom', $company->subscription_plan);
        $this->assertSame('Kwalitaria Plus', $company->custom_subscription_name);
        $this->assertSame('249.50', $company->custom_monthly_price);
        $this->assertSame(35, $company->max_users);
        $this->assertSame(8, $company->max_locations);
        $this->assertSame(100, $company->max_storage_gb);
        $this->assertSame('Kwalitaria Plus', $company->getPlanDetails()['name']);
    }

    public function test_super_admin_can_create_company_and_first_admin(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);

        $response = $this->actingAs($admin)->post(route('super-admin.companies.store'), [
            'company_name' => 'Nieuwe Klant BV',
            'admin_name' => 'Nieuwe Beheerder',
            'admin_email' => 'nieuwe-beheerder@example.com',
            'admin_password' => 'Tijdelijk!123',
            'subscription_plan' => 'starter',
            'billing_required' => '1',
            'company_phone' => '020 123 45 67',
        ]);

        $company = Company::withoutGlobalScopes()->where('name', 'Nieuwe Klant BV')->firstOrFail();

        $response->assertRedirect(route('super-admin.companies.show', $company));
        $this->assertDatabaseHas('users', [
            'company_id' => $company->id,
            'email' => 'nieuwe-beheerder@example.com',
            'role' => 'admin',
        ]);
    }

    public function test_super_admin_ai_import_is_attached_to_selected_company(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        $targetCompany = Company::create([
            'name' => 'Import Klant',
            'subscription_plan' => 'starter',
            'subscription_status' => 'active',
            'billing_required' => true,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('super-admin.companies.lists.ai-import', $targetCompany))
            ->assertOk()
            ->assertSee('Wordt gekoppeld aan Import Klant');

        $payload = json_encode(['lists' => [[
            'title' => 'Openingslijst',
            'priority' => 'medium',
            'schedule_type' => 'daily',
            'tasks' => [['title' => 'Deuren openen', 'is_required' => true]],
        ]]], JSON_THROW_ON_ERROR);

        $response = $this->actingAs($admin)->post(
            route('super-admin.companies.lists.ai-import.store', $targetCompany),
            ['import_payload' => $payload, 'selected_indices' => [0]],
        );

        $response->assertRedirect(route('super-admin.companies.show', $targetCompany));
        $this->assertDatabaseHas('lists', [
            'company_id' => $targetCompany->id,
            'title' => 'Openingslijst',
        ]);
    }

    public function test_super_admin_can_update_company_profile_and_add_admin(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        $company = Company::firstOrFail();

        $this->actingAs($admin)->put(route('super-admin.companies.profile.update', $company), [
            'name' => 'Gewijzigde Klant', 'email' => 'contact@klant.test', 'website' => 'https://klant.test', 'company_type' => 'horeca',
        ])->assertRedirect(route('super-admin.companies.show', ['company' => $company, 'section' => 'settings']));

        $this->actingAs($admin)->post(route('super-admin.companies.users.store', $company), [
            'name' => 'Extra Beheerder', 'email' => 'extra-admin@klant.test', 'password' => 'VeiligWachtwoord!123', 'role' => 'admin',
        ])->assertRedirect(route('super-admin.companies.show', ['company' => $company, 'section' => 'users']));

        $this->assertDatabaseHas('companies', ['id' => $company->id, 'name' => 'Gewijzigde Klant']);
        $this->assertDatabaseHas('users', ['company_id' => $company->id, 'email' => 'extra-admin@klant.test', 'role' => 'admin']);
    }

    public function test_super_admin_can_manage_company_microsoft_identity_settings(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        $company = Company::firstOrFail();
        $tenantId = '11111111-1111-4111-8111-111111111111';
        $clientId = '22222222-2222-4222-8222-222222222222';

        $this->actingAs($admin)
            ->get(route('super-admin.companies.show', ['company' => $company, 'section' => 'identity']))
            ->assertOk()
            ->assertSee('Microsoft Entra ID')
            ->assertSee('SCIM');

        $this->actingAs($admin)->put(route('super-admin.companies.identity.update', $company), [
            'domain' => 'voorbeeld.nl',
            'entra_enabled' => '1',
            'entra_mfa_required' => '1',
            'entra_tenant_id' => $tenantId,
            'entra_client_id' => $clientId,
            'entra_client_secret' => 'minimaal-zestien-tekens',
            'entra_admin_group_ids' => 'AAAAAAAA-AAAA-4AAA-8AAA-AAAAAAAAAAAA',
            'entra_employee_group_ids' => 'BBBBBBBB-BBBB-4BBB-8BBB-BBBBBBBBBBBB',
        ])->assertRedirect(route('super-admin.companies.show', ['company' => $company, 'section' => 'identity']));

        $company->refresh();
        $this->assertTrue($company->entra_enabled);
        $this->assertTrue($company->entra_mfa_required);
        $this->assertFalse($company->entra_sso_required);
        $this->assertSame('voorbeeld.nl', $company->domain);
        $this->assertSame(['aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa'], $company->entra_admin_group_ids);
    }

    public function test_super_admin_can_add_and_edit_an_employee_for_selected_company(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        $company = Company::firstOrFail();

        $this->actingAs($admin)->post(route('super-admin.companies.users.store', $company), [
            'name' => 'Nieuwe Medewerker',
            'email' => 'medewerker@klant.test',
            'password' => 'VeiligWachtwoord!123',
            'role' => 'employee',
            'phone' => '0612345678',
        ])->assertRedirect(route('super-admin.companies.show', ['company' => $company, 'section' => 'users']));

        $employee = User::where('email', 'medewerker@klant.test')->firstOrFail();
        $this->assertSame('employee', $employee->role);

        $this->actingAs($admin)->put(route('super-admin.companies.users.update', [$company, $employee]), [
            'name' => 'Aangepaste Medewerker',
            'email' => 'aangepast@klant.test',
            'role' => 'admin',
            'phone' => '0687654321',
        ])->assertRedirect(route('super-admin.companies.show', ['company' => $company, 'section' => 'users']));

        $this->assertDatabaseHas('users', [
            'id' => $employee->id,
            'name' => 'Aangepaste Medewerker',
            'email' => 'aangepast@klant.test',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('super-admin.companies.show', ['company' => $company, 'section' => 'users']))
            ->assertOk()
            ->assertSee('Gebruiker toevoegen')
            ->assertSee('Aangepaste Medewerker')
            ->assertSee('Bewerken');
    }

    public function test_super_admin_cannot_edit_a_user_through_another_company(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        $company = Company::firstOrFail();
        $otherCompany = Company::create(['name' => 'Andere klant', 'is_active' => true]);
        $user = User::where('company_id', $company->id)->firstOrFail();

        $this->actingAs($admin)->put(route('super-admin.companies.users.update', [$otherCompany, $user]), [
            'name' => 'Mag niet wijzigen',
            'email' => $user->email,
            'role' => 'employee',
        ])->assertNotFound();
    }

    public function test_platform_notification_records_broadcast_history(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);

        $this->actingAs($admin)->post(route('super-admin.communications.broadcast-notification'), [
            'title' => 'Onderhoud voltooid', 'message' => 'TaskCheck is weer volledig beschikbaar.', 'audience' => 'admins', 'severity' => 'success',
        ])->assertRedirect(route('super-admin.dashboard', ['tab' => 'communications']));

        $this->assertDatabaseHas('platform_broadcasts', [
            'channel' => 'in_app', 'title' => 'Onderhoud voltooid', 'audience' => 'admins', 'status' => 'sent',
        ]);
    }

    public function test_company_detail_only_counts_lists_from_selected_company(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        $selectedCompany = Company::firstOrFail();
        $otherCompany = Company::create([
            'name' => 'Andere organisatie',
            'subscription_plan' => 'starter',
            'subscription_status' => 'active',
            'billing_required' => true,
            'is_active' => true,
        ]);

        TaskList::withoutGlobalScopes()->create([
            'title' => 'Lijst van ander bedrijf',
            'company_id' => $otherCompany->id,
            'created_by' => $admin->id,
            'schedule_type' => 'daily',
            'priority' => 'medium',
            'is_active' => true,
            'is_template' => false,
        ]);

        $response = $this->actingAs($admin)->get(route('super-admin.companies.show', $selectedCompany));

        $response->assertOk();
        $this->assertSame(
            TaskList::withoutGlobalScopes()->where('company_id', $selectedCompany->id)->count(),
            $response->viewData('metrics')['lists']
        );
    }

    public function test_super_admin_can_manage_company_report_recipients(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        $company = Company::firstOrFail();

        $this->actingAs($admin)->put(route('super-admin.companies.reporting.update', $company), [
            'report_recipients' => [
                ['email' => 'dag@example.test', 'frequency' => 'daily', 'send_time' => '18:00', 'delivery_format' => 'pdf'],
                ['email' => 'week@example.test', 'frequency' => 'weekly', 'weekly_day' => 1, 'send_time' => '20:00', 'delivery_format' => 'both'],
            ],
        ])->assertRedirect(route('super-admin.companies.show', ['company' => $company, 'section' => 'reporting']));

        $this->assertDatabaseHas('company_report_recipients', ['company_id' => $company->id, 'email' => 'dag@example.test', 'frequency' => 'daily', 'delivery_format' => 'pdf']);
        $this->assertDatabaseHas('company_report_recipients', ['company_id' => $company->id, 'email' => 'week@example.test', 'frequency' => 'weekly', 'weekly_day' => 1, 'delivery_format' => 'both']);

        $this->actingAs($admin)
            ->get(route('super-admin.companies.show', ['company' => $company, 'section' => 'reporting']))
            ->assertOk()
            ->assertSee('Geplande rapportages')
            ->assertSee('dag@example.test');
    }

    public function test_super_admin_can_impersonate_a_company_user_and_return(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        $employee = User::where('role', 'employee')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('super-admin.companies.users.impersonate', [$employee->company_id, $employee]))
            ->assertRedirect(route('employee.dashboard'))
            ->assertSessionHas('impersonator_id', $admin->id)
            ->assertSessionHas('impersonated_user_id', $employee->id);
        $this->assertAuthenticatedAs($employee);

        $this->post(route('impersonation.stop'))
            ->assertRedirect(route('super-admin.dashboard', ['tab' => 'users']))
            ->assertSessionMissing('impersonator_id')
            ->assertSessionMissing('impersonated_user_id');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_super_admin_can_duplicate_a_company_with_lists_and_a_new_admin(): void
    {
        Notification::fake();
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        $source = Company::firstOrFail();
        $source->update(['departments' => ['Keuken'], 'working_hours' => Company::defaultWorkingHours()]);
        $location = Location::withoutGlobalScopes()->create(['company_id' => $source->id, 'name' => 'Papendrecht', 'is_active' => true]);
        $list = TaskList::withoutGlobalScopes()->create([
            'company_id' => $source->id,
            'created_by' => $admin->id,
            'location_id' => $location->id,
            'title' => 'Openingslijst',
            'schedule_type' => 'daily',
            'priority' => 'high',
            'is_active' => true,
        ]);
        $list->tasks()->create(['title' => 'Koeling controleren', 'created_by' => $admin->id, 'is_required' => true]);
        \App\Models\Billing\SubscriptionPlan::query()->create([
            'plan_key' => 'franchise_trial', 'name' => 'Franchise Trial', 'billing_period' => 'monthly',
            'billing_amount' => 199, 'price_monthly' => 199, 'price_annual' => 0,
            'trial_duration_value' => 1, 'trial_duration_unit' => 'months',
            'max_users' => 50, 'max_locations' => 20, 'max_storage_gb' => 100, 'features' => [],
        ]);

        $response = $this->actingAs($admin)->post(route('super-admin.companies.duplicate', $source), [
            'company_name' => 'Kwalitaria Nieuwe Franchisee',
            'admin_name' => 'Nieuwe Eigenaar',
            'admin_email' => 'nieuwe.eigenaar@example.test',
            'account_setup' => 'password',
            'admin_password' => 'Sterk-Wachtwoord-2026!',
            'subscription_plan' => 'franchise_trial',
            'copy_lists' => '1',
            'copy_locations' => '1',
            'copy_settings' => '1',
        ]);

        $copy = Company::where('name', 'Kwalitaria Nieuwe Franchisee')->firstOrFail();
        $response->assertRedirect(route('super-admin.companies.show', $copy));
        $this->assertDatabaseHas('users', ['company_id' => $copy->id, 'email' => 'nieuwe.eigenaar@example.test', 'role' => 'admin']);
        $newAdmin = User::where('email', 'nieuwe.eigenaar@example.test')->firstOrFail();
        $this->assertTrue(Hash::check('Sterk-Wachtwoord-2026!', $newAdmin->password));
        Notification::assertNothingSent();
        $this->assertDatabaseHas('locations', ['company_id' => $copy->id, 'name' => 'Papendrecht']);
        $copiedList = TaskList::withoutGlobalScopes()->where('company_id', $copy->id)->where('title', 'Openingslijst')->firstOrFail();
        $this->assertNotSame($list->id, $copiedList->id);
        $this->assertSame('Papendrecht', Location::withoutGlobalScopes()->findOrFail($copiedList->location_id)->name);
        $this->assertDatabaseHas('tasks', ['list_id' => $copiedList->id, 'title' => 'Koeling controleren']);
        $this->assertSame(['Keuken'], $copy->departments);
        $this->assertSame(now()->addMonthNoOverflow()->toDateString(), $copy->trial_ends_at?->toDateString());
        $this->assertSame($copy->trial_ends_at?->toDateString(), $copy->billing_start_date?->toDateString());
        $this->assertSame(0, \App\Models\Submissions\Submission::withoutGlobalScopes()->where('company_id', $copy->id)->count());
    }
}
