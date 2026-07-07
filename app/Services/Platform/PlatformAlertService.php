<?php

namespace App\Services\Platform;

use App\Mail\TaskCheckNotificationMail;
use App\Models\Platform\PlatformAlertLog;
use Illuminate\Support\Facades\Mail;

class PlatformAlertService
{
    public function __construct(
        protected PlatformHealthService $healthService
    ) {}

    /**
     * @return list<string> Alert keys that triggered an e-mail
     */
    public function checkAndNotify(): array
    {
        if (!config('platform_alerts.enabled', true)) {
            return [];
        }

        $recipients = $this->resolveRecipients();
        if ($recipients === []) {
            return [];
        }

        $snapshot = $this->healthService->snapshot();
        $sent = [];

        foreach ($snapshot['alerts'] as $alert) {
            if (!$alert['exceeded']) {
                continue;
            }

            if ($this->isInCooldown($alert['key'])) {
                continue;
            }

            $this->sendAlertMail($recipients, $alert, $snapshot['metrics']);
            $this->logSent($alert);

            $sent[] = $alert['key'];
        }

        return $sent;
    }

    /**
     * @return list<string>
     */
    protected function resolveRecipients(): array
    {
        $fromEnv = collect(explode(',', (string) config('platform_alerts.recipients', '')))
            ->map(fn ($email) => strtolower(trim($email)))
            ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->values()
            ->all();

        if ($fromEnv !== []) {
            return $fromEnv;
        }

        return collect(config('app.super_admin_emails', []))
            ->map(fn ($email) => strtolower(trim((string) $email)))
            ->filter()
            ->values()
            ->all();
    }

    protected function isInCooldown(string $alertKey): bool
    {
        $cooldown = max(1, (int) config('platform_alerts.cooldown_minutes', 60));

        return PlatformAlertLog::query()
            ->where('alert_key', $alertKey)
            ->where('sent_at', '>=', now()->subMinutes($cooldown))
            ->exists();
    }

    /**
     * @param  array{key: string, label: string, value: int, threshold: int, exceeded: bool}  $alert
     * @param  array<string, mixed>  $metrics
     */
    protected function sendAlertMail(array $recipients, array $alert, array $metrics): void
    {
        $dashboardUrl = route('super-admin.dashboard', ['tab' => 'monitoring']);

        $body = array_merge(
            $this->buildAlertIntro($alert, $metrics),
            $this->buildMetricsOverview($metrics),
            [''],
            [$this->buildAlertAdvice($alert['key'])],
        );

        $mail = new TaskCheckNotificationMail(
            subjectLine: '[TaskCheck] Platform alert: '.$alert['label'],
            greetingName: 'beheerder',
            title: 'Platformdrempel overschreden',
            bodyText: implode("\n", $body),
            ctaLabel: 'Open monitoring',
            ctaUrl: $dashboardUrl,
            metaText: 'Automatische melding · '.now()->timezone(config('app.timezone'))->format('d-m-Y H:i')
        );

        foreach ($recipients as $email) {
            Mail::to($email)->send($mail);
        }
    }

    /**
     * @param  array{key: string, label: string, value: int, threshold: int, exceeded: bool}  $alert
     */
    protected function logSent(array $alert): void
    {
        PlatformAlertLog::create([
            'alert_key' => $alert['key'],
            'metric_value' => $alert['value'],
            'threshold' => $alert['threshold'],
            'sent_at' => now(),
        ]);
    }

    /** @return list<string> */
    public function sendTestNotification(): array
    {
        $recipients = $this->resolveRecipients();
        if ($recipients === []) {
            return [];
        }

        $snapshot = $this->healthService->snapshot();
        $lines = ['Dit is een testmelding van het platformmonitoring-systeem.', '', 'Huidige waarden:'];

        foreach ($snapshot['alerts'] as $alert) {
            $status = $alert['exceeded'] ? 'OVERSCHREDEN' : 'ok';
            $lines[] = sprintf(
                '- %s: %s / drempel %s (%s)',
                $alert['label'],
                number_format($alert['value'], 0, ',', '.'),
                number_format($alert['threshold'], 0, ',', '.'),
                $status
            );
        }

        $mail = new TaskCheckNotificationMail(
            subjectLine: '[TaskCheck] Test platformmonitoring',
            greetingName: 'beheerder',
            title: 'Test platformmelding',
            bodyText: implode("\n", $lines),
            ctaLabel: 'Open monitoring',
            ctaUrl: route('super-admin.dashboard', ['tab' => 'monitoring']),
            metaText: 'Test · '.now()->timezone(config('app.timezone'))->format('d-m-Y H:i')
        );

        foreach ($recipients as $email) {
            Mail::to($email)->send($mail);
        }

        return $recipients;
    }

    /**
     * @param  array{key: string, label: string, value: int, threshold: int, exceeded: bool}  $alert
     * @param  array<string, mixed>  $metrics
     * @return list<string>
     */
    protected function buildAlertIntro(array $alert, array $metrics): array
    {
        $lines = [
            'Er is een drempelwaarde overschreden op het TaskCheck-platform.',
            '',
            'Melding: '.$alert['label'],
            'Huidige waarde: '.number_format($alert['value'], 0, ',', '.'),
            'Drempel: '.number_format($alert['threshold'], 0, ',', '.'),
        ];

        if ($alert['key'] === 'submissions_in_progress') {
            $window = (int) ($metrics['submissions_activity_window_minutes'] ?? 60);
            $total = (int) ($metrics['submissions_in_progress_total'] ?? 0);
            $minUsers = max(0, (int) config('platform_alerts.submissions_min_active_users', 5));

            $lines[] = 'Meetperiode: laatste '.$window.' minuten (recent bijgewerkt)';
            $lines[] = 'Totaal openstaand (alle tijd): '.number_format($total, 0, ',', '.');
            $lines[] = 'Vereist min. actieve gebruikers: '.$minUsers.' (nu: '.number_format($metrics['active_users'], 0, ',', '.').')';
        }

        return $lines;
    }

    /**
     * @param  array<string, mixed>  $metrics
     * @return list<string>
     */
    protected function buildMetricsOverview(array $metrics): array
    {
        $submissionWindow = (int) ($metrics['submissions_activity_window_minutes'] ?? 60);
        $submissionTotal = (int) ($metrics['submissions_in_progress_total'] ?? $metrics['submissions_in_progress']);

        return [
            '',
            'Overzicht op dit moment:',
            '- Actieve gebruikers ('.$metrics['session_window_minutes'].' min): '.number_format($metrics['active_users'], 0, ',', '.'),
            '- Actieve sessies: '.number_format($metrics['active_sessions'], 0, ',', '.'),
            '- Actieve inzendingen ('.$submissionWindow.' min): '.number_format($metrics['submissions_in_progress'], 0, ',', '.'),
            '- Openstaande inzendingen (totaal): '.number_format($submissionTotal, 0, ',', '.'),
            '- Wachtrij jobs: '.number_format($metrics['pending_jobs'], 0, ',', '.'),
            '- Mislukte jobs: '.number_format($metrics['failed_jobs'], 0, ',', '.'),
        ];
    }

    protected function buildAlertAdvice(string $alertKey): string
    {
        return match ($alertKey) {
            'submissions_in_progress' => 'Veel gelijktijdige checklist-activiteit. Controleer databasebelasting en of queue workers draaien als taken traag zijn.',
            'pending_jobs' => 'De job-wachtrij loopt op. Start of herstart queue workers en controleer of de queue-driver (Redis/database) bereikbaar is.',
            'failed_jobs' => 'Jobs falen herhaaldelijk. Bekijk failed_jobs in de monitoring of voer `php artisan queue:failed` uit.',
            'active_users', 'active_sessions' => 'Veel gelijktijdige gebruikers. Controleer servercapaciteit (CPU, geheugen) en database-verbindingen.',
            default => 'Controleer of de server voldoende capaciteit heeft (CPU, geheugen, database, queue workers).',
        };
    }
}
