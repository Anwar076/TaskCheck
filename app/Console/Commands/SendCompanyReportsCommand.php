<?php

namespace App\Console\Commands;

use App\Mail\CompanyReportMail;
use App\Models\Organisation\Company;
use App\Services\Admin\CompanyReportingService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendCompanyReportsCommand extends Command
{
    protected $signature = 'reports:send-company';

    protected $description = 'Verstuur dagelijkse of wekelijkse bedrijfsrapportages per e-mail';

    public function handle(CompanyReportingService $reportingService): int
    {
        $nowNl = now('Europe/Amsterdam')->seconds(0);
        $currentTime = $nowNl->format('H:i:00');

        $companies = Company::query()
            ->where('reporting_enabled', true)
            ->whereNotNull('reporting_frequency')
            ->whereNotNull('reporting_send_time')
            ->whereTime('reporting_send_time', '=', $currentTime)
            ->whereNotNull('email')
            ->get();

        if ($companies->isEmpty()) {
            $this->info('Geen rapportages op dit tijdstip.');

            return self::SUCCESS;
        }

        $sent = 0;
        /** @var Company $company */
        foreach ($companies as $company) {
            if (! $this->shouldSendNow($company, $nowNl)) {
                continue;
            }

            $frequency = (string) $company->reporting_frequency;
            $report = $reportingService->buildReport($company, $frequency, $nowNl);

            Mail::to($company->email)->send(new CompanyReportMail($company, $report));

            $company->update([
                'reporting_last_sent_at' => now(),
            ]);

            $sent++;
            $this->info(sprintf('Rapportage verstuurd: %s (%s)', $company->name, $company->email));
        }

        $this->info("Totaal verstuurd: {$sent}");

        return self::SUCCESS;
    }

    private function shouldSendNow(Company $company, Carbon $nowNl): bool
    {
        $lastSent = $company->reporting_last_sent_at?->copy()->timezone('Europe/Amsterdam');
        $frequency = (string) $company->reporting_frequency;

        if ($frequency === Company::REPORTING_FREQUENCY_WEEKLY) {
            $targetDay = (int) ($company->reporting_weekly_day ?? 1);
            if ((int) $nowNl->isoWeekday() !== $targetDay) {
                return false;
            }

            if (! $lastSent) {
                return true;
            }

            return ! $lastSent->isSameWeek($nowNl);
        }

        if (! $lastSent) {
            return true;
        }

        return ! $lastSent->isSameDay($nowNl);
    }
}
