<?php

namespace Tests\Feature;

use App\Models\Checklist\TaskList;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
