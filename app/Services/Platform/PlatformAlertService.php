<?php

namespace App\Services\Platform;

use App\Mail\TaskCheckNotificationMail;
use App\Models\PlatformAlertLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        $body = implode("\n", [
            'Er is een drempelwaarde overschreden op het TaskCheck-platform.',
            '',
            'Melding: '.$alert['label'],
            'Huidige waarde: '.number_format($alert['value'], 0, ',', '.'),
            'Drempel: '.number_format($alert['threshold'], 0, ',', '.'),
            '',
            'Overzicht op dit moment:',
            '- Actieve gebruikers ('.$metrics['session_window_minutes'].' min): '.number_format($metrics['active_users'], 0, ',', '.'),
            '- Actieve sessies: '.number_format($metrics['active_sessions'], 0, ',', '.'),
            '- Inzendingen bezig: '.number_format($metrics['submissions_in_progress'], 0, ',', '.'),
            '- Wachtrij jobs: '.number_format($metrics['pending_jobs'], 0, ',', '.'),
            '- Mislukte jobs: '.number_format($metrics['failed_jobs'], 0, ',', '.'),
            '',
            'Controleer of de server voldoende capaciteit heeft (CPU, geheugen, database, queue workers).',
        ]);

        $mail = new TaskCheckNotificationMail(
            subjectLine: '[TaskCheck] Platform alert: '.$alert['label'],
            greetingName: 'beheerder',
            title: 'Platformdrempel overschreden',
            bodyText: $body,
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
}
