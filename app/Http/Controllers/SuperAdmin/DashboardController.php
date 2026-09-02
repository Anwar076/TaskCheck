<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Billing\Invoice;
use App\Models\Checklist\Task;
use App\Models\Checklist\TaskList;
use App\Models\Communication\Notification;
use App\Models\Marketing\MarketingLinkCampaign;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use App\Models\Platform\IncidentTicket;
use App\Models\Platform\PlatformAlertLog;
use App\Models\Platform\PlatformBroadcast;
use App\Models\Submissions\Submission;
use App\Services\Platform\CompanyUsageService;
use App\Services\Platform\PlatformAlertService;
use App\Services\Platform\PlatformHealthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index(IncidentController $incidentController)
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
        $recentErrors = $incidentController->parsedErrors(30);
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
}
