<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Mail\TaskCheckNotificationMail;
use App\Models\Ai\AiUsageLog;
use App\Models\Billing\Invoice;
use App\Models\Billing\SubscriptionPlan;
use App\Models\Checklist\Task;
use App\Models\Checklist\TaskList;
use App\Models\Communication\Notification;
use App\Models\Marketing\MarketingLinkCampaign;
use App\Models\Organisation\Company;
use App\Models\Organisation\CompanyReportRecipient;
use App\Models\Organisation\Location;
use App\Models\Organisation\User;
use App\Models\Platform\IncidentTicket;
use App\Models\Platform\PlatformAlertLog;
use App\Models\Platform\PlatformBroadcast;
use App\Models\Submissions\Submission;
use App\Services\Admin\CompanyReportingService;
use App\Services\Billing\MollieService;
use App\Services\Platform\CompanyDuplicationService;
use App\Services\Platform\CompanyUsageService;
use App\Services\Platform\PlatformAlertService;
use App\Services\Platform\PlatformHealthService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function subscriptions()
    {
        $counts = Company::query()
            ->selectRaw('subscription_plan, COUNT(*) as company_count')
            ->groupBy('subscription_plan')
            ->pluck('company_count', 'subscription_plan');

        $plans = collect(Company::plans())->map(function (array $plan, string $key) use ($counts) {
            return array_merge($plan, [
                'key' => $key,
                'company_count' => (int) ($counts[$key] ?? 0),
            ]);
        });

        return view('super-admin.subscriptions.index', compact('plans'));
    }

    public function showSubscription(string $plan)
    {
        abort_unless(Company::plan($plan), 404);

        $companies = Company::query()
            ->where('subscription_plan', $plan)
            ->with(['users' => fn ($query) => $query->with('company')->orderBy('name')])
            ->withCount(['users', 'locations'])
            ->orderBy('name')
            ->get();

        return view('super-admin.subscriptions.show', [
            'planKey' => $plan,
            'plan' => Company::plan($plan),
            'companies' => $companies,
        ]);
    }

    public function createSubscriptionPlan()
    {
        return view('super-admin.subscriptions.create');
    }

    public function storeSubscriptionPlan(Request $request): RedirectResponse
    {
        $validated = $this->normalizeSubscriptionPrice($this->validateSubscriptionPlan($request));
        $baseKey = Str::slug($validated['name'], '_') ?: 'abonnement';
        $key = $baseKey;
        $suffix = 2;

        while (array_key_exists($key, Company::plans())) {
            $key = $baseKey.'_'.$suffix++;
        }

        SubscriptionPlan::query()->create(array_merge($validated, [
            'plan_key' => $key,
            'is_public' => false,
            'features' => $validated['features'] ?? [],
        ]));

        return redirect()->route('super-admin.subscriptions.show', $key)->with('success', 'Nieuw abonnement is aangemaakt.');
    }

    public function updateSubscriptionPlan(Request $request, string $plan): RedirectResponse
    {
        abort_unless(Company::plan($plan), 404);
        abort_if($plan === 'custom', 422, 'Maatwerkabonnementen worden per klant beheerd.');

        $validated = $this->normalizeSubscriptionPrice($this->validateSubscriptionPlan($request));

        SubscriptionPlan::query()->updateOrCreate(['plan_key' => $plan], $validated);

        Company::query()->where('subscription_plan', $plan)->where('subscription_plan', '!=', 'custom')->update([
            'max_users' => $validated['max_users'],
            'max_locations' => $validated['max_locations'],
            'max_storage_gb' => $validated['max_storage_gb'],
        ]);

        return redirect()->route('super-admin.subscriptions.show', $plan)->with('success', 'Abonnement is bijgewerkt.');
    }

    private function validateSubscriptionPlan(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'billing_period' => ['required', Rule::in(array_keys(Company::BILLING_PERIODS))],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'trial_duration_value' => ['required', 'integer', 'min:1', 'max:365'],
            'trial_duration_unit' => ['required', Rule::in(['days', 'weeks', 'months'])],
            'max_users' => ['required', 'integer', 'min:-1'],
            'max_locations' => ['required', 'integer', 'min:-1'],
            'max_storage_gb' => ['required', 'integer', 'min:-1'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', Rule::in(array_keys(Company::PLAN_FEATURES))],
        ]);

        $validated['features'] = $validated['features'] ?? [];

        return $validated;
    }

    private function normalizeSubscriptionPrice(array $validated): array
    {
        $period = $validated['billing_period'];
        $price = $validated['price'];
        unset($validated['billing_period'], $validated['price']);

        $validated['billing_period'] = $period;
        $validated['billing_amount'] = $price;
        $validated['price_monthly'] = $period === 'monthly' ? $price : 0;
        $validated['price_annual'] = $period === 'annual' ? $price : 0;

        return $validated;
    }

    public function index()
    {
        $companies = Company::query()
            ->withCount([
                'users as total_users',
                'users as admin_users' => fn ($query) => $query->where('role', 'admin'),
                'users as employee_users' => fn ($query) => $query->where('role', 'employee'),
                'locations as active_locations' => fn ($query) => $query->where('is_active', true),
            ])
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Company $company) {
                $company->storage_used_gb = $company->getStorageUsedGb();
                $company->billing_mode_label = $company->billing_required ? 'Maandelijks betalen' : 'Gratis toegang';

                return $company;
            });

        $totals = [
            'companies' => $companies->count(),
            'users' => (int) $companies->sum('total_users'),
            'admins' => (int) $companies->sum('admin_users'),
            'employees' => (int) $companies->sum('employee_users'),
            'locations' => (int) $companies->sum('active_locations'),
            'storage_gb' => round((float) $companies->sum('storage_used_gb'), 2),
            'task_lists' => TaskList::withoutGlobalScopes()->where(function ($q) {
                $q->where('is_template', false)->orWhereNull('is_template');
            })->count(),
            'tasks' => Task::query()
                ->join('lists', 'lists.id', '=', 'tasks.list_id')
                ->where(fn ($query) => $query->where('lists.is_template', false)->orWhereNull('lists.is_template'))
                ->count('tasks.id'),
            'submissions' => Submission::withoutGlobalScopes()->count(),
        ];

        $plans = $companies
            ->groupBy(fn (Company $company) => $company->subscription_plan ?: 'geen_plan')
            ->map(fn ($items) => $items->count())
            ->sortDesc();

        $users = User::query()
            ->with(['company:id,name', 'location:id,name'])
            ->whereNotNull('company_id')
            ->orderByDesc('created_at')
            ->get();

        $aiUsage = $this->getAiUsageSummary();
        $recentErrors = $this->getParsedErrors(30);
        $tickets = IncidentTicket::query()
            ->latest()
            ->limit(20)
            ->get();
        $invoices = Invoice::query()
            ->with('company:id,name')
            ->latest('paid_at')
            ->limit(100)
            ->get();
        $recentAnnouncementRows = Notification::query()
            ->where('type', 'platform_announcement')
            ->whereNotNull('data->campaign_id')
            ->latest()
            ->limit(300)
            ->get();
        $recentAnnouncements = $recentAnnouncementRows
            ->groupBy(fn (Notification $notification) => (string) data_get($notification->data, 'campaign_id'))
            ->map(function ($items) {
                /** @var \Illuminate\Support\Collection<int, Notification> $itemCollection */
                $itemCollection = $items instanceof \Illuminate\Support\Collection ? $items : collect([$items]);
                /** @var Notification|null $first */
                $first = $itemCollection->first();

                return [
                    'title' => (string) ($first?->title ?? 'Platform melding'),
                    'message' => (string) ($first?->message ?? ''),
                    'audience' => (string) data_get($first?->data, 'audience', 'all'),
                    'severity' => (string) data_get($first?->data, 'severity', 'info'),
                    'sent_at' => $first?->created_at,
                    'recipients' => $itemCollection->count(),
                ];
            })
            ->sortByDesc('sent_at')
            ->take(10)
            ->values();
        $broadcastHistory = Schema::hasTable('platform_broadcasts')
            ? PlatformBroadcast::query()->with('sender:id,name')->latest('sent_at')->limit(15)->get()
            : collect();
        $communicationCounts = [
            'active_companies' => Company::query()->where('is_active', true)->count(),
            'all_companies' => Company::query()->count(),
            'active_users' => User::query()->where('is_active', true)->count(),
            'all_users' => User::query()->count(),
            'active_admins' => User::query()->where('is_active', true)->whereIn('role', ['admin', 'super_admin'])->count(),
            'active_employees' => User::query()->where('is_active', true)->where('role', 'employee')->count(),
            'all_admins' => User::query()->whereIn('role', ['admin', 'super_admin'])->count(),
            'all_employees' => User::query()->where('role', 'employee')->count(),
        ];

        $marketingLinks = MarketingLinkCampaign::query()
            ->with('creator:id,name')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        $platformHealth = app(PlatformHealthService::class)->snapshot();
        $recentPlatformAlerts = PlatformAlertLog::query()
            ->orderByDesc('sent_at')
            ->limit(10)
            ->get();

        $allowedTabs = ['overview', 'communications', 'companies', 'users', 'usage', 'monitoring', 'invoices', 'templates'];
        $activeDashboardTab = request()->query('tab', 'overview');
        if (! in_array($activeDashboardTab, $allowedTabs, true)) {
            $activeDashboardTab = 'overview';
        }

        $usageFilter = request()->query('usage_filter', 'all');
        $usageOverview = app(CompanyUsageService::class)->buildUsageOverview(
            $usageFilter !== 'all' ? $usageFilter : null
        );

        return view('super-admin.dashboard', compact(
            'companies',
            'users',
            'totals',
            'plans',
            'aiUsage',
            'recentErrors',
            'tickets',
            'invoices',
            'recentAnnouncements',
            'broadcastHistory',
            'communicationCounts',
            'marketingLinks',
            'activeDashboardTab',
            'platformHealth',
            'recentPlatformAlerts',
            'usageOverview',
            'usageFilter'
        ));
    }

    public function sendPlatformAlertTest(PlatformAlertService $alerts): RedirectResponse
    {
        $recipients = $alerts->sendTestNotification();

        if ($recipients === []) {
            return redirect()
                ->route('super-admin.dashboard', ['tab' => 'monitoring'])
                ->with('error', 'Geen alert-e-mailadressen geconfigureerd (PLATFORM_ALERT_EMAIL of SUPER_ADMIN_EMAILS).');
        }

        return redirect()
            ->route('super-admin.dashboard', ['tab' => 'monitoring'])
            ->with('success', 'Testmelding verstuurd naar: '.implode(', ', $recipients));
    }

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

    public function updateCompanySubscription(Request $request, Company $company, MollieService $mollieService): RedirectResponse
    {
        $validated = $request->validate([
            'subscription_plan' => ['required', Rule::in(array_keys(Company::plans()))],
            'subscription_status' => ['required', Rule::in(['trial', 'active', 'cancelled', 'expired'])],
            'billing_required' => ['nullable', 'boolean'],
            'billing_period' => ['required', Rule::in(array_keys(Company::BILLING_PERIODS))],
            'billing_start_date' => ['nullable', 'date'],
            'trial_ends_at' => ['nullable', 'date'],
            'subscription_ends_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
            'custom_subscription_name' => ['nullable', 'required_if:subscription_plan,custom', 'string', 'max:100'],
            'custom_monthly_price' => ['nullable', 'required_if:subscription_plan,custom', 'numeric', 'min:0', 'max:999999.99'],
            'custom_max_users' => ['nullable', 'required_if:subscription_plan,custom', 'integer', 'min:-1'],
            'custom_max_locations' => ['nullable', 'required_if:subscription_plan,custom', 'integer', 'min:-1'],
            'custom_max_storage_gb' => ['nullable', 'required_if:subscription_plan,custom', 'integer', 'min:-1'],
        ]);

        $billingRequired = (bool) ($validated['billing_required'] ?? false);
        $plan = $validated['subscription_plan'];
        $planConfig = Company::plan($plan) ?? Company::plan('starter');
        $endDate = ! empty($validated['subscription_ends_at'])
            ? Carbon::parse($validated['subscription_ends_at'])->endOfDay()
            : null;
        $trialEndsAt = ! empty($validated['trial_ends_at']) ? Carbon::parse($validated['trial_ends_at'])->endOfDay() : null;
        $billingStartDate = ! empty($validated['billing_start_date'])
            ? Carbon::parse($validated['billing_start_date'])->startOfDay()
            : $trialEndsAt?->copy()->startOfDay();
        $subscriptionStatus = $validated['subscription_status'];

        if (! $billingRequired && $endDate) {
            $subscriptionStatus = $endDate->isPast() ? 'expired' : 'active';
            $billingStartDate = null;
        }

        if ($billingRequired
            && $subscriptionStatus === 'active'
            && $trialEndsAt?->isFuture()
            && ! $company->mollie_subscription_id
            && ! $endDate) {
            $subscriptionStatus = 'trial';
        }

        if (! $billingRequired && ! $endDate && $validated['subscription_status'] === 'active') {
            return redirect()->back()->withErrors([
                'subscription_ends_at' => 'Bij gratis toegang is een einddatum verplicht voor actieve status.',
            ])->withInput();
        }

        $isCustom = $plan === 'custom';
        $mollieSubscriptionId = $company->mollie_subscription_id;
        if ($company->mollie_customer_id && $company->mollie_subscription_id) {
            try {
                if (! $billingRequired || in_array($subscriptionStatus, ['cancelled', 'expired'], true)) {
                    $mollieService->cancelSubscription((string) $company->mollie_customer_id, (string) $company->mollie_subscription_id);
                    $mollieSubscriptionId = null;
                } else {
                    $netAmount = (float) ($isCustom ? $validated['custom_monthly_price'] : ($planConfig['billing_amount'] ?? $planConfig['price_monthly'] ?? 0));
                    $molliePayload = [
                        'amount' => ['currency' => 'EUR', 'value' => number_format($netAmount * 1.21, 2, '.', '')],
                        'interval' => Company::billingPeriod($validated['billing_period'])['mollie_interval'],
                        'description' => 'TaskCheck '.($isCustom ? $validated['custom_subscription_name'] : ($planConfig['name'] ?? $plan)).' abonnement',
                        'metadata' => ['company_id' => $company->id, 'plan' => $plan, 'interval' => Company::billingPeriod($validated['billing_period'])['mollie_interval']],
                    ];
                    if ($billingStartDate && $billingStartDate->isFuture()) {
                        $molliePayload['startDate'] = $billingStartDate->toDateString();
                    }
                    $mollieService->updateSubscription((string) $company->mollie_customer_id, (string) $company->mollie_subscription_id, $molliePayload);
                }
            } catch (\Throwable $exception) {
                report($exception);

                return back()->withInput()->with('error', 'De wijzigingen zijn niet opgeslagen, omdat Mollie het abonnement niet kon bijwerken: '.$exception->getMessage());
            }
        }

        $company->update([
            'subscription_plan' => $plan,
            'custom_subscription_name' => $isCustom ? $validated['custom_subscription_name'] : null,
            'custom_monthly_price' => $isCustom ? $validated['custom_monthly_price'] : null,
            'subscription_status' => $subscriptionStatus,
            'billing_required' => $billingRequired,
            'billing_period' => $validated['billing_period'],
            'billing_start_date' => $billingStartDate,
            'trial_ends_at' => $trialEndsAt,
            'subscription_ends_at' => $endDate,
            'mollie_subscription_id' => $mollieSubscriptionId,
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'max_users' => $isCustom ? $validated['custom_max_users'] : ($planConfig['max_users'] ?? $company->max_users),
            'max_locations' => $isCustom ? $validated['custom_max_locations'] : ($planConfig['max_locations'] ?? $company->max_locations),
            'max_storage_gb' => $isCustom ? $validated['custom_max_storage_gb'] : ($planConfig['max_storage_gb'] ?? $company->max_storage_gb),
        ]);

        return redirect()->route('super-admin.companies.show', ['company' => $company, 'section' => 'billing'])
            ->with('success', "Abonnement van {$company->name} is bijgewerkt.");
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

    public function storeCompanyUser(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12'],
            'role' => ['required', Rule::in(['admin', 'employee'])],
            'phone' => ['nullable', 'string', 'max:100'],
            'location_id' => ['nullable', Rule::exists('locations', 'id')->where(fn ($query) => $query->where('company_id', $company->id))],
        ]);

        User::create([
            'company_id' => $company->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'location_id' => $validated['location_id'] ?? null,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('super-admin.companies.show', ['company' => $company, 'section' => 'users'])
            ->with('success', "Gebruiker {$validated['name']} is toegevoegd aan {$company->name}.");
    }

    public function updateCompanyUser(Request $request, Company $company, User $user): RedirectResponse
    {
        abort_unless((int) $user->company_id === (int) $company->id, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'employee'])],
            'phone' => ['nullable', 'string', 'max:100'],
            'location_id' => ['nullable', Rule::exists('locations', 'id')->where(fn ($query) => $query->where('company_id', $company->id))],
            'password' => ['nullable', 'string', 'min:12'],
        ]);

        if ($user->is(Auth::user()) && $validated['role'] !== 'admin') {
            return back()->with('error', 'Je kunt je eigen superadminaccount niet naar medewerker wijzigen.');
        }

        $payload = collect($validated)->except('password')->all();
        if (! empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }
        $user->update($payload);

        return redirect()->route('super-admin.companies.show', ['company' => $company, 'section' => 'users'])
            ->with('success', "Gebruiker {$user->name} is bijgewerkt.");
    }

    public function sendCompanyUserPasswordReset(Company $company, User $user): RedirectResponse
    {
        abort_unless((int) $user->company_id === (int) $company->id, 404);

        $status = Password::sendResetLink(['email' => $user->email]);
        $message = $status === Password::RESET_LINK_SENT
            ? "Wachtwoordlink verstuurd naar {$user->email}."
            : 'De wachtwoordlink kon niet worden verstuurd. Controleer de mailinstellingen.';

        return redirect()->route('super-admin.companies.show', ['company' => $company, 'section' => 'users'])
            ->with($status === Password::RESET_LINK_SENT ? 'success' : 'error', $message);
    }

    public function toggleCompanyUser(Company $company, User $user): RedirectResponse
    {
        abort_unless((int) $user->company_id === (int) $company->id, 404);
        abort_if($user->is(Auth::user()), 422, 'Je kunt je eigen account hier niet blokkeren.');

        $user->update(['is_active' => ! $user->is_active]);

        return redirect()->route('super-admin.companies.show', ['company' => $company, 'section' => 'users'])
            ->with('success', "Account van {$user->name} is ".($user->is_active ? 'geactiveerd.' : 'geblokkeerd.'));
    }

    public function sendCompanyReportNow(
        Company $company,
        CompanyReportRecipient $recipient,
        CompanyReportingService $reportingService,
    ): JsonResponse {
        abort_unless((int) $recipient->company_id === (int) $company->id, 404);

        $reportingService->sendNow($recipient);

        return response()->json([
            'success' => true,
            'message' => "{$recipient->frequencyLabel()} verstuurd naar {$recipient->email}.",
        ]);
    }

    public function updateCompanyReporting(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'report_recipients' => ['nullable', 'array', 'max:20'],
            'report_recipients.*.id' => ['nullable', 'integer'],
            'report_recipients.*.email' => ['required', 'email', 'max:255'],
            'report_recipients.*.frequency' => ['required', Rule::in(['daily', 'weekly'])],
            'report_recipients.*.send_time' => ['required', 'date_format:H:i'],
            'report_recipients.*.weekly_day' => ['nullable', 'integer', 'between:1,7'],
            'report_recipients.*.delivery_format' => ['required', Rule::in(['email', 'pdf', 'both'])],
            'report_recipients.*.sections' => ['nullable', 'array'],
            'report_recipients.*.sections.summary' => ['nullable', 'boolean'],
            'report_recipients.*.sections.top_lists' => ['nullable', 'boolean'],
            'report_recipients.*.sections.employee_performance' => ['nullable', 'boolean'],
            'report_recipients.*.sections.attention_points' => ['nullable', 'boolean'],
            'report_recipients.*.sections.task_overview' => ['nullable', 'boolean'],
        ]);

        foreach ($validated['report_recipients'] ?? [] as $index => $recipient) {
            if (! in_array(true, CompanyReportRecipient::normalizeSections($recipient['sections'] ?? null), true)) {
                throw ValidationException::withMessages([
                    "report_recipients.{$index}.sections" => 'Kies minimaal één onderdeel voor deze rapportage.',
                ]);
            }
        }

        DB::transaction(function () use ($company, $validated) {
            $keptIds = [];
            foreach ($validated['report_recipients'] ?? [] as $row) {
                $recipient = ! empty($row['id']) ? $company->reportRecipients()->find($row['id']) : null;
                $recipient ??= $company->reportRecipients()->make();
                $recipient->fill([
                    'email' => $row['email'],
                    'frequency' => $row['frequency'],
                    'send_time' => $row['send_time'],
                    'weekly_day' => $row['frequency'] === 'weekly' ? ($row['weekly_day'] ?? 1) : null,
                    'delivery_format' => $row['delivery_format'],
                    'sections' => CompanyReportRecipient::normalizeSections($row['sections'] ?? null),
                    'is_enabled' => true,
                ])->save();
                $keptIds[] = $recipient->id;
            }

            $company->reportRecipients()
                ->when($keptIds !== [], fn ($query) => $query->whereNotIn('id', $keptIds))
                ->delete();
        });

        return redirect()->route('super-admin.companies.show', ['company' => $company, 'section' => 'reporting'])
            ->with('success', 'Rapportageplanning opgeslagen.');
    }

    public function sendBroadcastMail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:20000'],
            'include_inactive' => ['nullable', 'boolean'],
            'send_mode' => ['nullable', Rule::in(['send', 'test'])],
        ]);

        $includeInactive = (bool) ($validated['include_inactive'] ?? false);

        if (($validated['send_mode'] ?? 'send') === 'test') {
            Mail::to($request->user()->email)->send(new TaskCheckNotificationMail(
                subjectLine: '[TEST] '.(string) $validated['subject'],
                greetingName: $request->user()->name,
                title: (string) $validated['subject'],
                bodyText: (string) $validated['message'],
                ctaLabel: 'Open TaskCheck',
                ctaUrl: config('app.url'),
                metaText: 'Dit is een testweergave; klanten hebben dit bericht niet ontvangen.',
                showMarketing: true
            ));

            return redirect()->route('super-admin.dashboard', ['tab' => 'communications'])
                ->with('success', "Testmail verstuurd naar {$request->user()->email}. Er zijn geen klanten gemaild.");
        }

        $companies = Company::query()
            ->with(['users' => fn ($q) => $q->where('role', 'admin')->where('is_active', true)->orderBy('id')])
            ->when(! $includeInactive, fn ($q) => $q->where('is_active', true))
            ->get();

        $sent = 0;
        $failed = 0;

        foreach ($companies as $company) {
            $recipient = $company->email ?: optional($company->users->first())->email;
            if (! $recipient) {
                $failed++;

                continue;
            }

            try {
                Mail::to($recipient)->send(new TaskCheckNotificationMail(
                    subjectLine: (string) $validated['subject'],
                    greetingName: $company->name,
                    title: (string) $validated['subject'],
                    bodyText: (string) $validated['message'],
                    ctaLabel: 'Open TaskCheck',
                    ctaUrl: config('app.url'),
                    metaText: 'Je ontvangt dit bericht omdat je beheercontact bent van een TaskCheck organisatie.',
                    showMarketing: true
                ));

                $sent++;
            } catch (\Throwable $e) {
                report($e);
                $failed++;
            }
        }

        if (Schema::hasTable('platform_broadcasts')) {
            PlatformBroadcast::create([
                'sent_by' => Auth::id(), 'channel' => 'email', 'subject' => $validated['subject'],
                'message' => $validated['message'], 'audience' => $includeInactive ? 'all_companies' : 'active_companies',
                'recipients_count' => $sent, 'failed_count' => $failed, 'status' => $failed ? 'partially_sent' : 'sent',
                'sent_at' => now(),
            ]);
        }

        return redirect()->route('super-admin.dashboard', ['tab' => 'communications'])->with(
            'success',
            "Bulkmail verzonden. Succes: {$sent}, mislukt: {$failed}."
        );
    }

    public function sendBroadcastNotification(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:5000'],
            'audience' => ['required', Rule::in(['all', 'admins', 'employees'])],
            'severity' => ['required', Rule::in(['info', 'success', 'warning'])],
            'include_inactive' => ['nullable', 'boolean'],
        ]);

        $includeInactive = (bool) ($validated['include_inactive'] ?? false);
        $audience = (string) $validated['audience'];
        $campaignId = (string) Str::uuid();

        $usersQuery = User::query()
            ->when(! $includeInactive, fn ($q) => $q->where('is_active', true))
            ->when(
                $audience === 'admins',
                fn ($q) => $q->whereIn('role', ['admin', 'super_admin'])
            )
            ->when(
                $audience === 'employees',
                fn ($q) => $q->where('role', 'employee')
            );

        $users = $usersQuery->select('id', 'role')->get();
        $sent = 0;

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'platform_announcement',
                'title' => (string) $validated['title'],
                'message' => (string) $validated['message'],
                'data' => [
                    'campaign_id' => $campaignId,
                    'audience' => $audience,
                    'severity' => (string) $validated['severity'],
                    'sender' => 'super_admin',
                    'url' => '/dashboard',
                ],
            ]);
            $sent++;
        }

        if (Schema::hasTable('platform_broadcasts')) {
            PlatformBroadcast::create([
                'sent_by' => Auth::id(), 'channel' => 'in_app', 'title' => $validated['title'],
                'message' => $validated['message'], 'audience' => $audience,
                'recipients_count' => $sent, 'failed_count' => 0, 'status' => 'sent',
                'meta' => ['severity' => $validated['severity'], 'include_inactive' => $includeInactive], 'sent_at' => now(),
            ]);
        }

        return redirect()->route('super-admin.dashboard', ['tab' => 'communications'])->with(
            'success',
            "Platformmelding verstuurd naar {$sent} gebruikers."
        );
    }

    public function errorsFeed(): JsonResponse
    {
        return response()->json([
            'errors' => $this->getParsedErrors(30),
            'generated_at' => now()->toIso8601String(),
        ]);
    }

    public function createIncidentTicket(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fingerprint' => ['required', 'string', 'max:64'],
            'title' => ['required', 'string', 'max:255'],
            'error_message' => ['required', 'string', 'max:5000'],
            'context' => ['nullable', 'string', 'max:10000'],
            'error_occurred_at' => ['nullable', 'string', 'max:255'],
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'request_url' => ['nullable', 'string', 'max:2048'],
            'http_method' => ['nullable', 'string', 'max:10'],
            'user_agent' => ['nullable', 'string', 'max:2000'],
            'device_type' => ['nullable', 'string', 'max:20'],
            'ip_address' => ['nullable', 'ip'],
        ]);

        $occurredAt = null;
        if (! empty($validated['error_occurred_at'])) {
            try {
                $occurredAt = Carbon::parse($validated['error_occurred_at']);
            } catch (\Throwable) {
                $occurredAt = null;
            }
        }

        $existing = IncidentTicket::query()
            ->where('fingerprint', $validated['fingerprint'])
            ->where('created_at', '>=', now()->subHours(24))
            ->first();

        if ($existing) {
            return response()->json([
                'created' => false,
                'ticket_id' => $existing->id,
                'ticket' => $existing,
                'message' => 'Ticket bestond al.',
            ]);
        }

        $userAgent = $validated['user_agent'] ?? (string) $request->userAgent();
        $deviceType = $validated['device_type'] ?? $this->detectDeviceType($userAgent);

        $ticket = IncidentTicket::create([
            'company_id' => $validated['company_id'] ?? null,
            'reported_by_user_id' => Auth::id(),
            'fingerprint' => $validated['fingerprint'],
            'status' => 'open',
            'title' => $validated['title'],
            'error_message' => $validated['error_message'],
            'context' => $validated['context'] ?? null,
            'request_url' => $validated['request_url'] ?? null,
            'http_method' => isset($validated['http_method']) ? strtoupper($validated['http_method']) : null,
            'user_agent' => $userAgent,
            'device_type' => $deviceType,
            'ip_address' => $validated['ip_address'] ?? $request->ip(),
            'error_occurred_at' => $occurredAt,
        ]);

        return response()->json([
            'created' => true,
            'ticket_id' => $ticket->id,
            'ticket' => $ticket,
            'message' => 'Ticket aangemaakt.',
        ]);
    }

    public function showIncidentTicket(IncidentTicket $incident): JsonResponse
    {
        $displayTimezone = 'Europe/Amsterdam';
        $ticket = $incident->load(['company:id,name', 'reportedBy:id,name,email']);

        $occurredAt = $ticket->error_occurred_at?->copy()->timezone($displayTimezone)?->format('d-m-Y H:i:s');
        $createdAt = $ticket->created_at?->copy()->timezone($displayTimezone)?->format('d-m-Y H:i:s');

        return response()->json([
            'ticket' => $ticket,
            'display' => [
                'occurred_at' => $occurredAt,
                'created_at' => $createdAt,
            ],
        ]);
    }

    public function updateIncidentTicketStatus(Request $request, IncidentTicket $incident): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['open', 'resolved', 'ignored'])],
        ]);

        $incident->update([
            'status' => $validated['status'],
        ]);

        $label = match ($validated['status']) {
            'resolved' => 'afgerond',
            'ignored' => 'gearchiveerd',
            default => 'heropend',
        };

        return redirect()->route('super-admin.dashboard')
            ->with('success', "Ticket #{$incident->id} is {$label}.");
    }

    public function analyzeIncidentTicket(IncidentTicket $incident): JsonResponse
    {
        $apiKey = Config::get('services.openai.key');
        $model = Config::get('services.openai.model', 'gpt-4.1-mini');

        if (! $apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'OPENAI_API_KEY ontbreekt.',
            ], 422);
        }

        $codeContext = $this->collectStackTraceCodeContext($incident->context ?? '');
        $heuristicCodeContext = $this->collectHeuristicCodeContext($incident->error_message ?? '', $incident->context ?? '');

        $systemPrompt = <<<'PROMPT'
Je bent een senior Laravel debugging engineer.
Analyseer incidentdata en geef:
1) vermoedelijke root cause
2) exacte code-locaties (bestandsnamen/functies) waar het waarschijnlijk misgaat
3) korte concrete fix-stappen
4) regressie checks/tests

Schrijf in het Nederlands, compact en technisch.
Als code_context beschikbaar is:
- noem concrete bestanden + regels/functies
- geef een patch-richting (wat exact aanpassen)
- benoem welke checks/tests dit bevestigen
PROMPT;

        $userPayload = [
            'ticket' => [
                'id' => $incident->id,
                'title' => $incident->title,
                'status' => $incident->status,
                'error_message' => $incident->error_message,
                'context' => $incident->context,
                'request_url' => $incident->request_url,
                'http_method' => $incident->http_method,
                'device_type' => $incident->device_type,
                'user_agent' => $incident->user_agent,
            ],
            'code_context' => $codeContext,
            'heuristic_code_context' => $heuristicCodeContext,
        ];

        $response = Http::withToken($apiKey)
            ->timeout(45)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => 'Analyseer dit incident en geef concrete fix-richting: '.json_encode($userPayload, JSON_UNESCAPED_UNICODE)],
                ],
            ]);

        if (! $response->ok()) {
            return response()->json([
                'success' => false,
                'message' => 'AI analyse mislukt: '.$response->body(),
            ], 500);
        }

        $analysis = (string) ($response->json('choices.0.message.content') ?? '');
        $incident->update([
            'ai_analysis' => $analysis,
            'ai_model' => $model,
            'ai_analyzed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'analysis' => $analysis,
            'model' => $model,
            'analyzed_at' => optional($incident->fresh()->ai_analyzed_at)?->toIso8601String(),
        ]);
    }

    public function exportInvoicesCsv(): StreamedResponse
    {
        $filename = 'taskcheck-invoices-'.now()->timezone('Europe/Amsterdam')->format('Ymd-His').'.csv';

        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            fputcsv($handle, [
                'Invoice Number',
                'Company',
                'Paid At',
                'Description',
                'Currency',
                'Amount Ex VAT',
                'VAT Rate',
                'VAT Amount',
                'Amount Incl VAT',
                'Payment ID',
            ], ';');

            Invoice::query()
                ->with('company:id,name')
                ->latest('paid_at')
                ->chunk(500, function ($invoices) use ($handle): void {
                    foreach ($invoices as $invoice) {
                        fputcsv($handle, [
                            (string) $invoice->invoice_number,
                            (string) ($invoice->company?->name ?? ''),
                            optional($invoice->paid_at)?->timezone('Europe/Amsterdam')?->format('Y-m-d H:i:s') ?? '',
                            (string) ($invoice->description ?: 'TaskCheck abonnement'),
                            (string) $invoice->currency,
                            number_format((float) $invoice->amount_ex_vat, 2, '.', ''),
                            number_format((float) $invoice->vat_rate, 2, '.', ''),
                            number_format((float) $invoice->vat_amount, 2, '.', ''),
                            number_format((float) $invoice->amount, 2, '.', ''),
                            (string) $invoice->payment_id,
                        ], ';');
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    private function detectDeviceType(?string $userAgent): string
    {
        $ua = strtolower((string) $userAgent);
        if ($ua === '') {
            return 'unknown';
        }
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
            return 'mobile';
        }
        if (str_contains($ua, 'ipad') || str_contains($ua, 'tablet')) {
            return 'tablet';
        }

        return 'desktop';
    }

    private function collectStackTraceCodeContext(string $context): array
    {
        if ($context === '') {
            return [];
        }

        preg_match_all('/((?:[A-Za-z]:\\\\|\/)[^:\n]+\.php|(?:app|routes|database|resources|tests)\/[^:\n]+\.php):(\d+)/', $context, $matches, PREG_SET_ORDER);
        $snippets = [];
        $seen = [];

        foreach ($matches as $match) {
            $rawPath = $match[1] ?? null;
            $line = isset($match[2]) ? (int) $match[2] : null;
            if (! $rawPath || ! $line) {
                continue;
            }
            $filePath = $this->resolveCodePath($rawPath);
            if (! $filePath || isset($seen[$filePath.':'.$line])) {
                continue;
            }
            $seen[$filePath.':'.$line] = true;
            if (! File::exists($filePath)) {
                continue;
            }

            $allLines = @file($filePath);
            if (! is_array($allLines) || empty($allLines)) {
                continue;
            }

            $start = max(1, $line - 8);
            $end = min(count($allLines), $line + 8);
            $chunk = [];
            for ($i = $start; $i <= $end; $i++) {
                $prefix = $i === $line ? '>>' : '  ';
                $chunk[] = $prefix.$i.': '.rtrim((string) ($allLines[$i - 1] ?? ''), "\r\n");
            }

            $snippets[] = [
                'file' => $filePath,
                'line' => $line,
                'snippet' => implode("\n", $chunk),
            ];

            if (count($snippets) >= 5) {
                break;
            }
        }

        return $snippets;
    }

    private function resolveCodePath(string $rawPath): ?string
    {
        $normalized = str_replace('/', DIRECTORY_SEPARATOR, $rawPath);

        if (preg_match('/^[A-Za-z]:\\\\/', $normalized) || str_starts_with($normalized, DIRECTORY_SEPARATOR)) {
            return $normalized;
        }

        $projectPath = base_path($normalized);
        if (File::exists($projectPath)) {
            return $projectPath;
        }

        return null;
    }

    private function collectHeuristicCodeContext(string $errorMessage, string $context): array
    {
        $haystack = trim($errorMessage."\n".$context);
        if ($haystack === '') {
            return [];
        }

        preg_match_all('/\b([A-Z][A-Za-z0-9_]{4,})\b/', $haystack, $matches);
        $keywords = collect($matches[1] ?? [])
            ->map(fn (string $value) => trim($value))
            ->filter(fn (string $value) => ! in_array($value, ['Exception', 'Error', 'Class', 'Illuminate', 'Laravel'], true))
            ->unique()
            ->take(8)
            ->values();

        if ($keywords->isEmpty()) {
            return [];
        }

        $searchRoots = [
            app_path(),
            base_path('routes'),
            base_path('resources/views'),
            base_path('database'),
        ];

        $snippets = [];
        foreach ($searchRoots as $root) {
            if (! File::isDirectory($root)) {
                continue;
            }

            foreach (File::allFiles($root) as $file) {
                if ($file->getExtension() !== 'php' && $file->getExtension() !== 'blade.php') {
                    continue;
                }

                $contents = @file_get_contents($file->getPathname());
                if (! is_string($contents) || $contents === '') {
                    continue;
                }

                foreach ($keywords as $keyword) {
                    if (! str_contains($contents, $keyword)) {
                        continue;
                    }

                    $snippets[] = [
                        'file' => $file->getPathname(),
                        'keyword' => $keyword,
                    ];

                    if (count($snippets) >= 8) {
                        return $snippets;
                    }
                    break;
                }
            }
        }

        return $snippets;
    }

    private function getAiUsageSummary(): array
    {
        $candidates = ['ai_usage_logs', 'ai_usages', 'openai_usages'];

        foreach ($candidates as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $hasCompanyId = Schema::hasColumn($table, 'company_id');
            $tokenColumn = collect(['total_tokens', 'tokens_total', 'tokens', 'token_count', 'prompt_tokens'])
                ->first(fn ($col) => Schema::hasColumn($table, $col));

            if (! $hasCompanyId || ! $tokenColumn) {
                continue;
            }

            $rows = DB::table($table)
                ->select('company_id', DB::raw("SUM({$tokenColumn}) as tokens"))
                ->groupBy('company_id')
                ->get();

            return [
                'enabled' => true,
                'source_table' => $table,
                'total_tokens' => (int) $rows->sum('tokens'),
                'by_company' => $rows->mapWithKeys(fn ($row) => [(int) $row->company_id => (int) $row->tokens])->all(),
            ];
        }

        return [
            'enabled' => false,
            'source_table' => null,
            'total_tokens' => 0,
            'by_company' => [],
        ];
    }

    private function getParsedErrors(int $limit = 30): array
    {
        $logPath = storage_path('logs/laravel.log');
        if (! File::exists($logPath)) {
            return [];
        }

        $lines = @file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (! $lines) {
            return [];
        }

        $displayTimezone = 'Europe/Amsterdam';

        return collect($lines)
            ->filter(fn ($line) => str_contains($line, '.ERROR:') || str_contains($line, 'local.ERROR:'))
            ->take(-max($limit * 5, 100))
            ->reverse()
            ->values()
            ->map(function (string $line) use ($displayTimezone) {
                $timestamp = null;
                $level = 'ERROR';
                $message = $line;

                if (preg_match('/^\[(.*?)\]\s+\w+\.([A-Z]+):\s*(.*)$/', $line, $matches)) {
                    $rawTimestamp = trim($matches[1]);
                    try {
                        $timestamp = Carbon::parse($rawTimestamp, 'UTC')
                            ->setTimezone($displayTimezone)
                            ->format('Y-m-d H:i:s');
                    } catch (\Throwable) {
                        $timestamp = $rawTimestamp;
                    }
                    $level = trim($matches[2]);
                    $message = trim($matches[3]);
                }

                $companyId = null;
                if (preg_match('/"company_id"\s*:\s*(\d+)/', $line, $companyMatches)) {
                    $companyId = (int) $companyMatches[1];
                }

                $requestUrl = null;
                if (preg_match('/"url"\s*:\s*"([^"]+)"/', $line, $urlMatches)) {
                    $requestUrl = $urlMatches[1];
                } elseif (preg_match('/https?:\/\/[^\s"]+/', $line, $directUrlMatch)) {
                    $requestUrl = $directUrlMatch[0];
                }

                $httpMethod = null;
                if (preg_match('/"method"\s*:\s*"([A-Z]+)"/', $line, $methodMatches)) {
                    $httpMethod = strtoupper($methodMatches[1]);
                }

                $userAgent = null;
                if (preg_match('/"user_agent"\s*:\s*"([^"]+)"/', $line, $uaMatches)) {
                    $userAgent = $uaMatches[1];
                }

                $deviceType = $this->detectDeviceType($userAgent);

                $safeMessage = str_replace(base_path(), '[app]', mb_substr($message, 0, 1200));
                $safeRaw = str_replace(base_path(), '[app]', $line);
                $signature = preg_replace(['/\{.*$/s', '/\b\d+\b/', '/\s+/'], ['', '#', ' '], $safeMessage);

                return [
                    'fingerprint' => sha1((string) $signature),
                    'timestamp' => $timestamp,
                    'level' => $level,
                    'message' => $safeMessage,
                    'raw' => $safeRaw,
                    'company_id' => $companyId,
                    'request_url' => $requestUrl,
                    'http_method' => $httpMethod,
                    'user_agent' => $userAgent,
                    'device_type' => $deviceType,
                ];
            })
            ->groupBy('fingerprint')
            ->map(function ($items) {
                $latest = $items->first();
                $oldest = $items->last();
                $latest['count'] = $items->count();
                $latest['first_seen'] = $oldest['timestamp'] ?? null;
                $latest['last_seen'] = $latest['timestamp'] ?? null;

                return $latest;
            })
            ->take($limit)
            ->values()
            ->all();
    }
}
