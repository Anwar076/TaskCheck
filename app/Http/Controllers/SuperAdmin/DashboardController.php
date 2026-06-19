<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Mail\TaskCheckNotificationMail;
use App\Models\Organisation\Company;
use App\Models\Marketing\MarketingLinkCampaign;
use App\Models\Platform\PlatformAlertLog;
use App\Services\Platform\CompanyUsageService;
use App\Services\Platform\PlatformAlertService;
use App\Services\Platform\PlatformHealthService;
use App\Models\Platform\IncidentTicket;
use App\Models\Billing\Invoice;
use App\Models\Communication\Notification;
use App\Models\Submissions\Submission;
use App\Models\Checklist\Task;
use App\Models\Checklist\TaskList;
use Carbon\Carbon;
use App\Models\Organisation\User;
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
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
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
            'task_lists' => TaskList::query()->where(function ($q) {
                $q->where('is_template', false)->orWhereNull('is_template');
            })->count(),
            'tasks' => Task::query()->count(),
            'submissions' => Submission::query()->count(),
        ];

        $plans = $companies
            ->groupBy(fn (Company $company) => $company->subscription_plan ?: 'geen_plan')
            ->map(fn ($items) => $items->count())
            ->sortDesc();

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

        $allowedTabs = ['communications', 'companies', 'usage', 'monitoring', 'invoices', 'templates'];
        $activeDashboardTab = request()->query('tab', 'communications');
        if (!in_array($activeDashboardTab, $allowedTabs, true)) {
            $activeDashboardTab = 'communications';
        }

        $usageFilter = request()->query('usage_filter', 'all');
        $usageOverview = app(CompanyUsageService::class)->buildUsageOverview(
            $usageFilter !== 'all' ? $usageFilter : null
        );

        return view('super-admin.dashboard', compact(
            'companies',
            'totals',
            'plans',
            'aiUsage',
            'recentErrors',
            'tickets',
            'invoices',
            'recentAnnouncements',
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

    public function storeCompany(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'admin_password' => ['required', 'string', 'min:8'],
            'subscription_plan' => ['required', Rule::in(array_keys(Company::PLANS))],
            'billing_required' => ['nullable', 'boolean'],
            'access_end_date' => ['nullable', 'date', 'after_or_equal:today'],
            'company_phone' => ['nullable', 'string', 'max:100'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'company_website' => ['nullable', 'string', 'max:255'],
        ]);

        $billingRequired = (bool) ($validated['billing_required'] ?? false);
        if (!$billingRequired && empty($validated['access_end_date'])) {
            return redirect()->back()->withErrors([
                'access_end_date' => 'Bij gratis toegang is een einddatum verplicht.',
            ])->withInput();
        }

        $plan = $validated['subscription_plan'];
        $planConfig = Company::PLANS[$plan] ?? Company::PLANS['starter'];
        $subscriptionEndsAt = $billingRequired
            ? null
            : Carbon::parse($validated['access_end_date'])->endOfDay();

        DB::transaction(function () use ($validated, $plan, $planConfig, $billingRequired, $subscriptionEndsAt) {
            $company = Company::create([
                'name' => $validated['company_name'],
                'phone' => $validated['company_phone'] ?? null,
                'address' => $validated['company_address'] ?? null,
                'website' => $validated['company_website'] ?? null,
                'email' => $validated['admin_email'],
                'subscription_plan' => $plan,
                'subscription_status' => 'active',
                'subscription_ends_at' => $subscriptionEndsAt,
                'billing_required' => $billingRequired,
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
        });

        return redirect()->route('super-admin.dashboard')
            ->with('success', 'Bedrijf en admin account zijn aangemaakt.');
    }

    public function updateCompanySubscription(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'subscription_plan' => ['required', Rule::in(array_keys(Company::PLANS))],
            'subscription_status' => ['required', Rule::in(['trial', 'active', 'cancelled', 'expired'])],
            'billing_required' => ['nullable', 'boolean'],
            'subscription_ends_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $billingRequired = (bool) ($validated['billing_required'] ?? false);
        $plan = $validated['subscription_plan'];
        $planConfig = Company::PLANS[$plan] ?? Company::PLANS['starter'];
        $endDate = !empty($validated['subscription_ends_at'])
            ? Carbon::parse($validated['subscription_ends_at'])->endOfDay()
            : null;

        if (!$billingRequired && !$endDate && $validated['subscription_status'] === 'active') {
            return redirect()->back()->withErrors([
                'subscription_ends_at' => 'Bij gratis toegang is een einddatum verplicht voor actieve status.',
            ]);
        }

        $company->update([
            'subscription_plan' => $plan,
            'subscription_status' => $validated['subscription_status'],
            'billing_required' => $billingRequired,
            'subscription_ends_at' => $billingRequired ? null : $endDate,
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'max_users' => $planConfig['max_users'] ?? $company->max_users,
            'max_locations' => $planConfig['max_locations'] ?? $company->max_locations,
            'max_storage_gb' => $planConfig['max_storage_gb'] ?? $company->max_storage_gb,
        ]);

        return redirect()->route('super-admin.dashboard')
            ->with('success', "Abonnement van {$company->name} is bijgewerkt.");
    }

    public function sendBroadcastMail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:20000'],
            'include_inactive' => ['nullable', 'boolean'],
        ]);

        $includeInactive = (bool) ($validated['include_inactive'] ?? false);

        $companies = Company::query()
            ->with(['users' => fn ($q) => $q->where('role', 'admin')->where('is_active', true)->orderBy('id')])
            ->when(!$includeInactive, fn ($q) => $q->where('is_active', true))
            ->get();

        $sent = 0;
        $failed = 0;

        foreach ($companies as $company) {
            $recipient = $company->email ?: optional($company->users->first())->email;
            if (!$recipient) {
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

        return redirect()->route('super-admin.dashboard')->with(
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
            ->when(!$includeInactive, fn ($q) => $q->where('is_active', true))
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
        if (!empty($validated['error_occurred_at'])) {
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

        if (!$apiKey) {
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
                    ['role' => 'user', 'content' => 'Analyseer dit incident en geef concrete fix-richting: ' . json_encode($userPayload, JSON_UNESCAPED_UNICODE)],
                ],
            ]);

        if (!$response->ok()) {
            return response()->json([
                'success' => false,
                'message' => 'AI analyse mislukt: ' . $response->body(),
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
        $filename = 'taskcheck-invoices-' . now()->timezone('Europe/Amsterdam')->format('Ymd-His') . '.csv';

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
            if (!$rawPath || !$line) {
                continue;
            }
            $filePath = $this->resolveCodePath($rawPath);
            if (!$filePath || isset($seen[$filePath . ':' . $line])) {
                continue;
            }
            $seen[$filePath . ':' . $line] = true;
            if (!File::exists($filePath)) {
                continue;
            }

            $allLines = @file($filePath);
            if (!is_array($allLines) || empty($allLines)) {
                continue;
            }

            $start = max(1, $line - 8);
            $end = min(count($allLines), $line + 8);
            $chunk = [];
            for ($i = $start; $i <= $end; $i++) {
                $prefix = $i === $line ? '>>' : '  ';
                $chunk[] = $prefix . $i . ': ' . rtrim((string) ($allLines[$i - 1] ?? ''), "\r\n");
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
        $haystack = trim($errorMessage . "\n" . $context);
        if ($haystack === '') {
            return [];
        }

        preg_match_all('/\b([A-Z][A-Za-z0-9_]{4,})\b/', $haystack, $matches);
        $keywords = collect($matches[1] ?? [])
            ->map(fn (string $value) => trim($value))
            ->filter(fn (string $value) => !in_array($value, ['Exception', 'Error', 'Class', 'Illuminate', 'Laravel'], true))
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
            if (!File::isDirectory($root)) {
                continue;
            }

            foreach (File::allFiles($root) as $file) {
                if ($file->getExtension() !== 'php' && $file->getExtension() !== 'blade.php') {
                    continue;
                }

                $contents = @file_get_contents($file->getPathname());
                if (!is_string($contents) || $contents === '') {
                    continue;
                }

                foreach ($keywords as $keyword) {
                    if (!str_contains($contents, $keyword)) {
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
            if (!Schema::hasTable($table)) {
                continue;
            }

            $hasCompanyId = Schema::hasColumn($table, 'company_id');
            $tokenColumn = collect(['total_tokens', 'tokens_total', 'tokens', 'token_count', 'prompt_tokens'])
                ->first(fn ($col) => Schema::hasColumn($table, $col));

            if (!$hasCompanyId || !$tokenColumn) {
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
        if (!File::exists($logPath)) {
            return [];
        }

        $lines = @file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$lines) {
            return [];
        }

        $displayTimezone = 'Europe/Amsterdam';

        return collect($lines)
            ->filter(fn ($line) => str_contains($line, '.ERROR:') || str_contains($line, 'local.ERROR:'))
            ->take(-$limit)
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

                return [
                    'fingerprint' => sha1($line),
                    'timestamp' => $timestamp,
                    'level' => $level,
                    'message' => mb_substr($message, 0, 1200),
                    'raw' => $line,
                    'company_id' => $companyId,
                    'request_url' => $requestUrl,
                    'http_method' => $httpMethod,
                    'user_agent' => $userAgent,
                    'device_type' => $deviceType,
                ];
            })
            ->all();
    }
}

