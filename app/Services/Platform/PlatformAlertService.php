<?php

namespace App\Services\Platform;

use App\Mail\TaskCheckNotificationMail;
use App\Models\Platform\PlatformAlertLog;
use App\Models\Platform\PlatformAlertState;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PlatformAlertService
{
    public function __construct(protected PlatformHealthService $healthService) {}

    /** @return list<string> Metrics for which an incident or recovery mail was sent. */
    public function checkAndNotify(): array
    {
        if (! config('platform_alerts.enabled', true) || $this->resolveRecipients() === []) {
            return [];
        }

        // Also protects manual command runs and multiple scheduler processes.
        return Cache::lock('platform-alert-check', 300)->get(function () {
            $snapshot = $this->healthService->snapshot();
            $sent = [];
            foreach ($snapshot['alerts'] as $alert) {
                $state = PlatformAlertState::firstOrCreate(['alert_key' => $alert['key']]);
                // A gap in observations is not evidence of continuous recovery.
                if ($state->healthy_since && $state->updated_at->lt(now()->subMinutes(10))) {
                    $state->healthy_since = null;
                }
                $state->touch();
                if (! $alert['available'] || $alert['threshold'] <= 0) {
                    $state->update(['healthy_since' => null]);

                    continue;
                }
                if ($alert['exceeded']) {
                    $state->update(['healthy_since' => null]);
                    if ($state->opened_at) {
                        continue;
                    }
                    $event = 'opened';
                } else {
                    if (! $state->opened_at) {
                        continue;
                    }
                    if (! $state->healthy_since) {
                        $state->update(['healthy_since' => now()]);
                    }
                    if ($state->healthy_since->gt(now()->subMinutes(max(1, (int) config('platform_alerts.recovery_minutes', 15))))) {
                        continue;
                    }
                    $event = 'recovered';
                }

                $this->sendEventMail($alert, $snapshot['metrics'], $event);
                // A mail failure leaves the incident eligible for a later retry.
                DB::transaction(function () use ($state, $alert, $event) {
                    PlatformAlertLog::create([
                        'alert_key' => $alert['key'],
                        'event_type' => $event,
                        'metric_value' => $alert['value'],
                        'threshold' => $alert['threshold'],
                        'sent_at' => now(),
                    ]);
                    $state->update([
                        'opened_at' => $event === 'opened' ? now() : null,
                        'healthy_since' => null,
                    ]);
                });
                $sent[] = $alert['key'];
            }

            return $sent;
        }) ?: [];
    }

    /** @return list<string> */
    protected function resolveRecipients(): array
    {
        $configured = trim((string) config('platform_alerts.recipients', ''));

        return collect($configured !== '' ? explode(',', $configured) : config('app.super_admin_emails', []))
            ->map(fn ($email) => strtolower(trim($email)))
            ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()->values()->all();
    }

    protected function sendEventMail(array $alert, array $metrics, string $event): void
    {
        $recovered = $event === 'recovered';
        $intro = $recovered
            ? 'De meting blijft sinds '.config('platform_alerts.recovery_minutes', 15).' minuten onder de meldingsgrens.'
            : 'Er is een nieuw verwerkingsprobleem gemeten. Je krijgt hiervoor geen herhaalde waarschuwingen zolang dit probleem aanhoudt.';
        $advice = match ($alert['key']) {
            'failed_jobs' => $recovered
                ? 'De recente foutfrequentie is weer onder de grens. Eerder mislukte taken zijn hiermee niet automatisch opnieuw uitgevoerd of opgelost.'
                : 'Controleer de foutmeldingen van de mislukte achtergrondtaken en herstel de oorzaak voordat je taken opnieuw probeert.',
            default => $recovered
                ? 'Het aantal vertraagde taken is weer onder de grens.'
                : 'Controleer of de achtergrondverwerking draait en waarom taken blijven wachten of niet worden afgerond.',
        };
        $this->sendMail(
            $this->resolveRecipients(),
            ($recovered ? 'Herstel: ' : 'Verwerkingsprobleem: ').$alert['label'],
            implode("\n", [$intro, '', $alert['label'].': '.$alert['value'].' / grens '.$alert['threshold'], $this->measurementDescription($alert['key'], $metrics), '', $advice]),
        );
    }

    protected function measurementDescription(string $key, array $metrics): string
    {
        return $key === 'failed_jobs'
            ? 'Mislukte taken in de laatste '.$metrics['failed_jobs_window_minutes'].' minuten; oudere fouten tellen niet mee.'
            : 'Taken die minstens '.$metrics['stalled_jobs_minutes'].' minuten uitvoerbaar zijn en nog wachten of een oude verwerkingsreservering hebben. Toekomstige taken tellen niet mee.';
    }

    protected function sendMail(array $recipients, string $title, string $body): void
    {
        foreach ($recipients as $email) {
            Mail::to($email)->send(new TaskCheckNotificationMail(
                subjectLine: '[TaskCheck] '.$title,
                greetingName: 'beheerder',
                title: $title,
                bodyText: $body,
                ctaLabel: 'Open monitoring',
                ctaUrl: route('super-admin.dashboard', ['tab' => 'monitoring']),
                metaText: 'Platformmonitoring · '.now()->timezone(config('app.timezone'))->format('d-m-Y H:i'),
            ));
        }
    }

    /** A test mail does not open or resolve incidents. @return list<string> */
    public function sendTestNotification(): array
    {
        $recipients = $this->resolveRecipients();
        if ($recipients === []) {
            return [];
        }
        $snapshot = $this->healthService->snapshot();
        $lines = ['Dit is een handmatige testmelding. De status van problemen verandert hierdoor niet.', ''];
        foreach ($snapshot['alerts'] as $alert) {
            $status = ! $alert['available'] ? 'niet beschikbaar' : ($alert['threshold'] <= 0 ? 'meldingen uitgeschakeld' : ($alert['exceeded'] ? 'boven de grens' : 'onder de grens'));
            $lines[] = $alert['label'].': '.($alert['value'] ?? '—').' ('.$status.')';
            $lines[] = $this->measurementDescription($alert['key'], $snapshot['metrics']);
        }
        $this->sendMail($recipients, 'Test platformmonitoring', implode("\n", $lines));

        return $recipients;
    }
}
