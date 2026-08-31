<?php

namespace App\Console\Commands;

use App\Mail\CompanyReportMail;
use App\Models\Organisation\Company;
use App\Models\Organisation\CompanyReportRecipient;
use App\Services\Admin\CompanyReportingService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendCompanyReportsCommand extends Command
{
    protected $signature = 'reports:send-company {--force : Testverzending, negeert tijdstip en dubbele-check}';
    protected $description = 'Verstuur geplande dagelijkse en wekelijkse bedrijfsrapportages';

    public function handle(CompanyReportingService $reportingService): int
    {
        $nowNl = now('Europe/Amsterdam')->seconds(0);
        $force = (bool) $this->option('force');
        $query = CompanyReportRecipient::query()->with('company')->where('is_enabled', true);
        if (! $force) {
            $query->whereTime('send_time', $nowNl->format('H:i:s'));
        }

        $sent = 0;
        foreach ($query->get() as $schedule) {
            if (! $schedule->company || (! $force && ! $this->shouldSendNow($schedule, $nowNl))) {
                continue;
            }
            $report = $reportingService->buildReport($schedule->company, $schedule->frequency, $nowNl);
            Mail::to($schedule->email)->send(new CompanyReportMail($schedule->company, $report, $schedule->delivery_format));
            $schedule->update(['last_sent_at' => now()]);
            $sent++;
            $this->info("Rapportage verstuurd: {$schedule->company->name} ({$schedule->email})");
        }

        $this->info("Totaal verstuurd: {$sent}");
        return self::SUCCESS;
    }

    private function shouldSendNow(CompanyReportRecipient $schedule, Carbon $nowNl): bool
    {
        $lastSent = $schedule->last_sent_at?->copy()->timezone('Europe/Amsterdam');
        if ($schedule->frequency === Company::REPORTING_FREQUENCY_WEEKLY) {
            if ((int) $nowNl->isoWeekday() !== (int) ($schedule->weekly_day ?? 1)) {
                return false;
            }

            return ! $lastSent || ! $lastSent->isSameWeek($nowNl);
        }
        return ! $lastSent || ! $lastSent->isSameDay($nowNl);
    }
}
