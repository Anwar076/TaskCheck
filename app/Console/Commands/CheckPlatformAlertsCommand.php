<?php

namespace App\Console\Commands;

use App\Services\Platform\PlatformAlertService;
use App\Services\Platform\PlatformHealthService;
use Illuminate\Console\Command;

class CheckPlatformAlertsCommand extends Command
{
    protected $signature = 'platform:check-alerts {--test : Stuur een testmail met huidige waarden}';

    protected $description = 'Controleer platformdrempels en stuur e-mail naar super admin bij overschrijding';

    public function handle(PlatformAlertService $alerts, PlatformHealthService $health): int
    {
        $snapshot = $health->snapshot();

        $this->table(
            ['Meting', 'Waarde', 'Drempel', 'Status'],
            collect($snapshot['alerts'])->map(fn ($a) => [
                $a['label'],
                $a['value'],
                $a['threshold'],
                $a['exceeded'] ? 'ALERT' : 'ok',
            ])->all()
        );

        if ($this->option('test')) {
            $recipients = $alerts->sendTestNotification();
            if ($recipients === []) {
                $this->error('Geen ontvangers. Zet PLATFORM_ALERT_EMAIL of SUPER_ADMIN_EMAILS in .env.');

                return self::FAILURE;
            }

            $this->info('Testmail verstuurd naar: '.implode(', ', $recipients));

            return self::SUCCESS;
        }

        $sent = $alerts->checkAndNotify();

        if ($sent === []) {
            $this->info('Geen nieuwe alerts (of cooldown actief).');

            return self::SUCCESS;
        }

        $this->warn('Alerts verstuurd: '.implode(', ', $sent));

        return self::SUCCESS;
    }
}
