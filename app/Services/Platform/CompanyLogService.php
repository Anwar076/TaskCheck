<?php

namespace App\Services\Platform;

use App\Models\Organisation\Company;
use App\Models\Organisation\Location;
use App\Models\Organisation\User;
use App\Models\Platform\CompanyLogEntry;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class CompanyLogService
{
    // Explicit allowlists: credentials, tokens, preferences and login timestamps
    // must never be copied into the company timeline.
    private const COMPANY_FIELDS = [
        'name' => 'Naam', 'company_type' => 'Bedrijfstype', 'domain' => 'Domein',
        'address' => 'Adres', 'phone' => 'Telefoon', 'email' => 'E-mailadres',
        'website' => 'Website', 'description' => 'Omschrijving', 'is_active' => 'Actief',
        'subscription_plan' => 'Abonnement', 'subscription_status' => 'Abonnementsstatus',
        'custom_subscription_name' => 'Abonnementsnaam', 'custom_monthly_price' => 'Maandprijs',
        'pending_subscription_plan' => 'Gepland abonnement', 'trial_ends_at' => 'Einde proefperiode',
        'subscription_ends_at' => 'Einde abonnement', 'billing_required' => 'Facturatie verplicht',
        'billing_period' => 'Facturatieperiode', 'billing_start_date' => 'Start facturatie',
        'max_users' => 'Gebruikerslimiet', 'max_locations' => 'Locatielimiet', 'max_storage_gb' => 'Opslaglimiet (GB)',
        'departments' => 'Afdelingen', 'working_hours' => 'Werktijden', 'calendar_time_mode' => 'Agendatijden',
        'reporting_enabled' => 'Rapportages actief', 'reporting_frequency' => 'Rapportagefrequentie',
        'reporting_send_time' => 'Rapportagetijd', 'reporting_weekly_day' => 'Rapportagedag',
        'entra_enabled' => 'Microsoft SSO actief', 'entra_sso_required' => 'SSO verplicht',
        'entra_mfa_required' => 'MFA verplicht',
    ];

    private const USER_FIELDS = [
        'name' => 'Naam', 'email' => 'E-mailadres', 'role' => 'Rol',
        'phone' => 'Telefoon', 'department' => 'Afdeling', 'is_active' => 'Actief',
        'location_id' => 'Locatie',
    ];

    private const LOCATION_FIELDS = [
        'name' => 'Naam', 'address' => 'Adres', 'street' => 'Straat',
        'house_number' => 'Huisnummer', 'postal_code' => 'Postcode',
        'city' => 'Plaats', 'is_active' => 'Actief',
    ];

    public function actor(): array
    {
        $actor = auth()->user();
        $name = $actor?->name ?? 'Systeem';
        if (request()->hasSession() && request()->session()->has('impersonator_id')) {
            $original = User::find(request()->session()->get('impersonator_id'));
            if ($original) {
                $name = $original->name.' (namens '.$name.')';
                $actor = $original;
            }
        }

        if ($actor && ! User::whereKey($actor->id)->exists()) {
            $actor = null;
        }

        return ['actor_id' => $actor?->id, 'actor_name' => mb_substr($name, 0, 255)];
    }

    public function record(Model $model, string $event): void
    {
        [$category, $label, $fields] = match (true) {
            $model instanceof Company => ['company', 'Bedrijf', self::COMPANY_FIELDS],
            $model instanceof User => ['user', 'Gebruiker', self::USER_FIELDS],
            $model instanceof Location => ['location', 'Locatie', self::LOCATION_FIELDS],
        };
        $companyId = $model instanceof Company ? $model->id : $model->company_id;
        $changes = [];
        if ($event === 'updated') {
            foreach ($fields as $field => $fieldLabel) {
                if ($model->wasChanged($field)) {
                    $changes[$field] = [
                        'label' => $fieldLabel,
                        'before' => $this->display($field, $model->getOriginal($field)),
                        'after' => $this->display($field, $model->getAttribute($field)),
                    ];
                }
            }
            if (! ($model instanceof Company) && $model->wasChanged('company_id')) {
                $oldCompanyId = $model->getOriginal('company_id');
                if ($oldCompanyId) {
                    $this->write($oldCompanyId, $model, $category, 'moved_out', $label.' overgeplaatst: '.$model->name);
                }
                if ($companyId) {
                    $this->write($companyId, $model, $category, 'moved_in', $label.' toegevoegd door overplaatsing: '.$model->name);
                }

                return;
            }
            if ($changes === []) {
                return;
            }
        }
        if (! $companyId) {
            return;
        }
        $action = match ($event) {
            'created' => 'toegevoegd', 'deleted' => 'verwijderd', default => 'gewijzigd'
        };
        $body = collect($changes)->map(fn ($change) => $change['label'].': '.$change['before'].' → '.$change['after'])->implode("\n");
        if ($event === 'created' && $model instanceof User) {
            $body = $model->email."\n".$this->display('role', $model->role);
        }
        $this->write($companyId, $model, $category, $event, $label.' '.$action.': '.$model->name, $body, $changes);
    }

    private function write(int $companyId, Model $model, string $category, string $event, string $title, string $body = '', array $changes = []): void
    {
        CompanyLogEntry::create(array_merge($this->actor(), [
            'company_id' => $companyId, 'category' => $category, 'event' => $event,
            'subject_type' => $category, 'subject_id' => $model->id,
            'title' => mb_substr($title, 0, 255), 'body' => $body ?: null,
            'changes' => $changes ?: null, 'occurred_at' => now(),
        ]));
    }

    private function display(string $field, mixed $value): string
    {
        if ($value === null || $value === '') {
            return '—';
        }
        if (is_bool($value)) {
            return $value ? 'Ja' : 'Nee';
        }
        if ($value instanceof DateTimeInterface) {
            return $value->format('d-m-Y H:i');
        }
        if ($field === 'role') {
            return ['admin' => 'Beheerder', 'employee' => 'Medewerker'][$value] ?? (string) $value;
        }
        if ($field === 'location_id') {
            return Location::withoutGlobalScope('company')->find($value)?->name ?? 'Locatie #'.$value;
        }

        return is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : (string) $value;
    }
}
