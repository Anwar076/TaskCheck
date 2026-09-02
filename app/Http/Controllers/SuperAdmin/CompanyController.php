<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Ai\AiUsageLog;
use App\Models\Billing\Invoice;
use App\Models\Checklist\Task;
use App\Models\Checklist\TaskList;
use App\Models\Organisation\Company;
use App\Models\Organisation\Location;
use App\Models\Organisation\User;
use App\Models\Submissions\Submission;
use App\Services\Billing\MollieService;
use App\Services\Platform\CompanyDuplicationService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function showCompany(Company $company, MollieService $mollieService)
    {
        $users = User::query()->where('company_id', $company->id);
        $locations = Location::withoutGlobalScope('company')->where('company_id', $company->id);
        $lists = TaskList::withoutGlobalScope('company')->where('company_id', $company->id);
        $submissions = Submission::withoutGlobalScope('company')->where('company_id', $company->id);

        $metrics = [
            'users' => (clone $users)->count(),
            'active_users' => (clone $users)->where('is_active', true)->count(),
            'admins' => (clone $users)->where('role', 'admin')->count(),
            'employees' => (clone $users)->where('role', 'employee')->count(),
            'locations' => (clone $locations)->where('is_active', true)->count(),
            'lists' => (clone $lists)->where(fn ($query) => $query->where('is_template', false)->orWhereNull('is_template'))->count(),
            'active_lists' => (clone $lists)->where('is_active', true)->where(fn ($query) => $query->where('is_template', false)->orWhereNull('is_template'))->count(),
            'tasks' => Task::query()->whereIn('list_id', (clone $lists)->select('id'))->count(),
            'submissions' => (clone $submissions)->count(),
            'completed_submissions' => (clone $submissions)->whereIn('status', ['completed', 'reviewed'])->count(),
            'submissions_30d' => (clone $submissions)->where('created_at', '>=', now()->subDays(30))->count(),
            'storage_gb' => $company->getStorageUsedGb(),
        ];
        $metrics['completion_rate'] = $metrics['submissions'] > 0
            ? (int) round(($metrics['completed_submissions'] / $metrics['submissions']) * 100)
            : 0;

        $recentUsers = (clone $users)
            ->with('location:id,name')
            ->latest()
            ->limit(8)
            ->get();
        $recentLists = (clone $lists)
            ->withCount(['tasks', 'submissions'])
            ->latest()
            ->limit(8)
            ->get();
        $recentInvoices = Invoice::query()
            ->where('company_id', $company->id)
            ->latest('paid_at')
            ->limit(8)
            ->get();
        $companyUsers = (clone $users)->with('location:id,name')->orderBy('name')->get();
        $companyLists = (clone $lists)->withCount(['tasks', 'submissions'])->latest()->get();
        $companyInvoices = Invoice::query()->where('company_id', $company->id)->latest('paid_at')->get();
        $companyLocations = (clone $locations)->orderBy('name')->get();
        $company->load(['reportRecipients' => fn ($query) => $query->orderBy('id')]);
        $aiUsageByFeature = Schema::hasTable('ai_usage_logs')
            ? AiUsageLog::query()
                ->where('company_id', $company->id)
                ->select('feature', DB::raw('SUM(total_tokens) as tokens'))
                ->groupBy('feature')
                ->orderByDesc('tokens')
                ->get()
            : collect();
        $aiTokens = (int) $aiUsageByFeature->sum('tokens');
        $lastActivityAt = (clone $submissions)->max('updated_at');
        $mollieBilling = [
            'connected' => filled($company->mollie_customer_id) && filled($company->mollie_subscription_id),
            'available' => true,
            'status' => null,
            'next_payment_date' => $company->billing_start_date ?: $company->trial_ends_at?->copy()->startOfDay(),
            'interval' => Company::billingPeriod($company->billing_period ?: 'monthly')['mollie_interval'],
            'amount' => null,
            'currency' => 'EUR',
        ];
        if ($mollieBilling['connected']) {
            try {
                $mollieSubscription = $mollieService->getSubscription(
                    (string) $company->mollie_customer_id,
                    (string) $company->mollie_subscription_id,
                );
                $mollieBilling['status'] = $mollieSubscription['status'] ?? null;
                $mollieBilling['next_payment_date'] = filled($mollieSubscription['nextPaymentDate'] ?? null)
                    ? Carbon::parse($mollieSubscription['nextPaymentDate'])->startOfDay()
                    : null;
                $mollieBilling['interval'] = $mollieSubscription['interval'] ?? null;
                $mollieBilling['amount'] = data_get($mollieSubscription, 'amount.value');
                $mollieBilling['currency'] = data_get($mollieSubscription, 'amount.currency', 'EUR');
            } catch (\Throwable $exception) {
                report($exception);
                $mollieBilling['available'] = false;
            }
        }

        return view('super-admin.companies.show', compact(
            'company',
            'metrics',
            'recentUsers',
            'recentLists',
            'recentInvoices',
            'companyUsers',
            'companyLists',
            'companyInvoices',
            'companyLocations',
            'aiUsageByFeature',
            'aiTokens',
            'lastActivityAt',
            'mollieBilling',
        ));
    }

    public function createCompany()
    {
        return view('super-admin.companies.create');
    }

    public function storeCompany(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'admin_password' => ['required', 'string', 'min:8'],
            'subscription_plan' => ['required', Rule::in(array_keys(Company::plans()))],
            'billing_required' => ['nullable', 'boolean'],
            'access_end_date' => ['nullable', 'date', 'after_or_equal:today'],
            'company_phone' => ['nullable', 'string', 'max:100'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'company_website' => ['nullable', 'string', 'max:255'],
        ]);

        $billingRequired = (bool) ($validated['billing_required'] ?? false);
        if (! $billingRequired && empty($validated['access_end_date'])) {
            return redirect()->back()->withErrors([
                'access_end_date' => 'Bij gratis toegang is een einddatum verplicht.',
            ])->withInput();
        }

        $plan = $validated['subscription_plan'];
        $planConfig = Company::plan($plan) ?? Company::plan('starter');
        $subscriptionEndsAt = $billingRequired
            ? null
            : Carbon::parse($validated['access_end_date'])->endOfDay();
        $trialEnd = $billingRequired ? Company::trialEndForPlan($plan) : null;

        $company = DB::transaction(function () use ($validated, $plan, $planConfig, $billingRequired, $subscriptionEndsAt, $trialEnd) {
            $company = Company::create([
                'name' => $validated['company_name'],
                'phone' => $validated['company_phone'] ?? null,
                'address' => $validated['company_address'] ?? null,
                'website' => $validated['company_website'] ?? null,
                'email' => $validated['admin_email'],
                'subscription_plan' => $plan,
                'subscription_status' => $billingRequired ? 'trial' : 'active',
                'trial_ends_at' => $trialEnd,
                'subscription_ends_at' => $subscriptionEndsAt,
                'billing_required' => $billingRequired,
                'billing_period' => $planConfig['billing_period'] ?? 'monthly',
                'billing_start_date' => $trialEnd?->toDateString(),
                'signup_source' => Company::SIGNUP_SOURCE_MANAGED,
                'max_users' => $planConfig['max_users'] ?? 5,
                'max_locations' => $planConfig['max_locations'] ?? 1,
                'max_storage_gb' => $planConfig['max_storage_gb'] ?? 5,
                'is_active' => true,
            ]);

            User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'company_id' => $company->id,
            ]);

            return $company;
        });

        return redirect()->route('super-admin.companies.show', $company)
            ->with('success', 'Bedrijf en admin account zijn aangemaakt.');
    }

    public function duplicateCompany(Request $request, Company $company, CompanyDuplicationService $duplicator): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'account_setup' => ['required', Rule::in(['invite', 'password'])],
            'admin_password' => ['nullable', 'required_if:account_setup,password', 'string', 'min:12', 'max:255'],
            'subscription_plan' => ['required', Rule::in(array_keys(Company::plans()))],
            'copy_lists' => ['nullable', 'boolean'],
            'copy_locations' => ['nullable', 'boolean'],
            'copy_settings' => ['nullable', 'boolean'],
            'copy_reporting' => ['nullable', 'boolean'],
        ]);

        foreach (['copy_lists', 'copy_locations', 'copy_settings', 'copy_reporting'] as $option) {
            $validated[$option] = $request->boolean($option);
        }

        $locationLimit = (int) (Company::plan($validated['subscription_plan'])['max_locations'] ?? 1);
        $sourceLocationCount = $company->locations()->count();
        if ($validated['copy_locations'] && $locationLimit !== -1 && $sourceLocationCount > $locationLimit) {
            return back()->withErrors([
                'subscription_plan' => "Dit abonnement ondersteunt {$locationLimit} locatie(s), terwijl de bron {$sourceLocationCount} locaties heeft.",
            ])->withInput();
        }

        $result = $duplicator->duplicate($company, $validated);
        if ($validated['account_setup'] === 'invite') {
            $result['admin']->sendInvitationNotification($request->user());
        }

        return redirect()->route('super-admin.companies.show', $result['company'])
            ->with('success', "Bedrijf gedupliceerd met {$result['lists']} lijsten, {$result['tasks']} taken en {$result['locations']} locaties. ".($validated['account_setup'] === 'invite' ? 'De nieuwe beheerder heeft een uitnodiging ontvangen.' : 'Het beheerderswachtwoord is ingesteld zonder e-mail te versturen.'));
    }

    public function destroyCompany(Request $request, Company $company, MollieService $mollieService): RedirectResponse
    {
        if ((int) $request->user()->company_id === (int) $company->id) {
            return back()->with('error', 'Je kunt het bedrijf van je eigen actieve superadmin-account niet verwijderen.');
        }

        $request->validate([
            'confirmation_name' => ['required', 'string', Rule::in([$company->name])],
        ], [
            'confirmation_name.in' => 'Vul de volledige bedrijfsnaam exact in om het bedrijf te verwijderen.',
        ]);

        if ($company->mollie_customer_id && $company->mollie_subscription_id) {
            try {
                $mollieService->cancelSubscription(
                    (string) $company->mollie_customer_id,
                    (string) $company->mollie_subscription_id,
                );
            } catch (\Throwable $exception) {
                report($exception);

                return back()->with('error', 'Het bedrijf is niet verwijderd, omdat het Mollie-abonnement niet kon worden stopgezet: '.$exception->getMessage());
            }
        }

        $companyName = $company->name;
        $userCount = $company->users()->count();
        $submissionIds = Submission::withoutGlobalScope('company')
            ->where('company_id', $company->id)
            ->pluck('id');
        $logoPath = $company->logo_path;

        DB::transaction(fn () => $company->delete());

        try {
            foreach ($submissionIds as $submissionId) {
                Storage::disk('public')->deleteDirectory('submissions/'.$submissionId);
            }
            if ($logoPath) {
                Storage::disk('public')->delete($logoPath);
            }
        } catch (\Throwable $exception) {
            report($exception);
        }

        return redirect()->route('super-admin.dashboard', ['tab' => 'companies'])
            ->with('success', "{$companyName} en {$userCount} onderliggende gebruiker(s) zijn definitief verwijderd.");
    }

    public function updateCompanyProfile(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'company_type' => ['nullable', Rule::in(['horeca', 'cleaning', 'other'])],
        ]);

        $company->update($validated);

        return redirect()->route('super-admin.companies.show', ['company' => $company, 'section' => 'settings'])
            ->with('success', "Bedrijfsgegevens van {$company->name} zijn bijgewerkt.");
    }

    public function updateCompanyIdentity(Request $request, Company $company): RedirectResponse
    {
        $data = $request->validate([
            'domain' => ['required', 'string', 'max:255', 'regex:/^(?!-)[a-z0-9.-]+(?<!-)$/i'],
            'entra_enabled' => ['nullable', 'boolean'],
            'entra_sso_required' => ['nullable', 'boolean'],
            'entra_mfa_required' => ['nullable', 'boolean'],
            'entra_tenant_id' => ['nullable', 'uuid'],
            'entra_client_id' => ['nullable', 'uuid'],
            'entra_client_secret' => ['nullable', 'string', 'min:16', 'max:2048'],
            'entra_admin_group_ids' => ['nullable', 'string', 'max:4000'],
            'entra_employee_group_ids' => ['nullable', 'string', 'max:4000'],
        ]);

        foreach (['entra_enabled', 'entra_sso_required', 'entra_mfa_required'] as $field) {
            $data[$field] = $request->boolean($field);
        }

        if ($data['entra_enabled'] && (! $data['entra_tenant_id'] || ! $data['entra_client_id'] || (! $request->filled('entra_client_secret') && ! $company->entra_client_secret))) {
            return back()->withErrors(['entra_enabled' => 'Tenant ID, client ID en client secret zijn verplicht om Entra te activeren.'])->withInput();
        }

        if (! $request->filled('entra_client_secret')) {
            unset($data['entra_client_secret']);
        }

        foreach (['entra_admin_group_ids', 'entra_employee_group_ids'] as $field) {
            $data[$field] = collect(preg_split('/[\s,;]+/', $data[$field] ?? ''))->filter()
                ->map(fn ($id) => Str::lower(trim($id)))->unique()->values()->all();
        }

        $company->update($data);

        return redirect()->route('super-admin.companies.show', ['company' => $company, 'section' => 'identity'])
            ->with('success', "Microsoft SSO van {$company->name} is bijgewerkt.");
    }

    public function rotateCompanyScimToken(Company $company): RedirectResponse
    {
        $token = 'tc_scim_'.Str::random(64);
        $company->forceFill([
            'scim_endpoint_key' => $company->scim_endpoint_key ?: Str::uuid(),
            'scim_token_hash' => hash('sha256', $token),
        ])->save();

        return redirect()->route('super-admin.companies.show', ['company' => $company, 'section' => 'identity'])
            ->with('success', 'Nieuwe SCIM-token aangemaakt. Kopieer deze nu; hij wordt niet opnieuw getoond.')
            ->with('scim_token', $token);
    }
}
