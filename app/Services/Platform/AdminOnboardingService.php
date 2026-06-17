<?php

namespace App\Services\Platform;

use App\Models\Company;
use App\Models\ListAssignment;
use App\Models\TaskList;
use App\Models\User;

class AdminOnboardingService
{
    /**
     * @return array<string, mixed>
     */
    public function context(?Company $company): array
    {
        if (!$company || !$company->needsOnboarding()) {
            return ['active' => false];
        }

        $step = $company->onboarding_step === 'lists'
            ? Company::ONBOARDING_STEP_LIST_CHOICE
            : $company->onboarding_step;

        $employeeCount = User::where('company_id', $company->id)
            ->where('role', 'employee')
            ->where('is_active', true)
            ->count();

        return [
            'active' => true,
            'step' => $step,
            'step_number' => $this->stepNumber($step),
            'total_steps' => 5,
            'employee_count' => $employeeCount,
            'can_continue_users' => $employeeCount >= 1,
            'show_list_choice' => $step === Company::ONBOARDING_STEP_LIST_CHOICE,
            'show_assign_hint' => $step === Company::ONBOARDING_STEP_ASSIGN,
            'list_id' => $company->onboarding_list_id,
            'list_mode' => $company->onboarding_list_mode,
            'tour' => $this->buildTour($step, $employeeCount, $company, request()->route()?->getName()),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function helpContext(?Company $company, ?string $routeName): array
    {
        $user = auth()->user();

        if (!$user?->isAdmin() || $user->isSuperAdmin() || !$company?->hasCompletedOnboarding()) {
            return ['enabled' => false];
        }

        return [
            'enabled' => true,
            'just_completed' => session()->has('onboarding_completed'),
            'tour' => $this->buildHelpTour($company, $routeName),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildHelpTour(Company $company, ?string $routeName): array
    {
        $employeeCount = User::where('company_id', $company->id)
            ->where('role', 'employee')
            ->where('is_active', true)
            ->count();

        $slides = $this->helpSlidesForRoute($routeName, $employeeCount, $company);

        return [
            'mode' => 'help',
            'auto_open' => false,
            'fab_label' => 'Heb je hulp nodig?',
            'slides' => array_values($slides),
            'step_number' => 1,
            'total_steps' => max(1, count($slides)),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function helpSlidesForRoute(?string $routeName, int $employeeCount, Company $company): array
    {
        $routeName ??= '';

        if (str_starts_with($routeName, 'admin.settings')) {
            return $this->stripHelpSlides([
                [
                    'target' => '[data-onboarding-target="org-fields"]',
                    'title' => 'Organisatiegegevens',
                    'body' => 'Hier beheer je naam, adres, telefoon en e-mail van je organisatie.',
                    'placement' => 'left',
                    'clickTarget' => false,
                    'cta' => 'Pas je gegevens aan in het gemarkeerde blok.',
                ],
                [
                    'target' => '[data-onboarding-target="org-save"]',
                    'title' => 'Gegevens opslaan',
                    'body' => 'Vergeet niet op Opslaan te klikken na wijzigingen.',
                    'placement' => 'top',
                    'clickTarget' => true,
                    'highlightPad' => 8,
                    'cta' => 'Klik op Opslaan om te bevestigen.',
                ],
            ]);
        }

        if (str_starts_with($routeName, 'admin.users')) {
            return $this->stripHelpSlides($this->usersTourSlides($employeeCount, $routeName));
        }

        if ($routeName === 'admin.templates.index') {
            return $this->stripHelpSlides($this->listCreateTourSlides($company, 'admin.templates.index'));
        }

        if ($routeName === 'admin.lists.create') {
            return $this->stripHelpSlides($this->listCreateTourSlides($company, 'admin.lists.create'));
        }

        if ($routeName === 'admin.lists.calendar') {
            return $this->stripHelpSlides($this->companyCalendarTourSlides());
        }

        if ($routeName === 'admin.lists.show') {
            return $this->stripHelpSlides($this->listShowTourSlides('admin.lists.show'));
        }

        return [
            [
                'target' => null,
                'title' => 'Hulp bij TaskCheck',
                'body' => 'Op elke pagina leggen we uit wat je kunt doen. Ga naar Instellingen, Gebruikers, Templates, Lijsten of Agenda en klik opnieuw op Heb je hulp nodig?',
                'placement' => 'center',
            ],
        ];
    }

    /**
     * @param array<int, array<string, mixed>> $slides
     * @return array<int, array<string, mixed>>
     */
    private function stripHelpSlides(array $slides): array
    {
        return array_values(array_map(function (array $slide) {
            unset($slide['action'], $slide['showCustomListOption']);

            return $slide;
        }, $slides));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function usersTourSlides(int $employeeCount, ?string $routeName): array
    {
        if ($routeName === 'admin.users.create') {
            return [
                [
                    'target' => '[data-onboarding-target="user-fields"]',
                    'title' => 'Vul de medewerker in',
                    'body' => 'Naam, e-mail en wachtwoord zijn verplicht. Rol staat standaard op Medewerker.',
                    'placement' => 'left',
                    'clickTarget' => false,
                    'cta' => 'Vul de gemarkeerde velden in.',
                ],
                [
                    'target' => '[data-onboarding-target="user-save"]',
                    'title' => 'Maak de medewerker aan',
                    'body' => 'Als alles klopt, klik op Gebruiker aanmaken om het account op te slaan.',
                    'placement' => 'top',
                    'clickTarget' => true,
                    'highlightPad' => 8,
                    'cta' => 'Klik op de blauwe knop Gebruiker aanmaken.',
                ],
            ];
        }

        if ($employeeCount >= 1) {
            return [
                [
                    'target' => null,
                    'title' => 'Team is klaar',
                    'body' => 'Je hebt ' . $employeeCount . ' medewerker(s) toegevoegd. Ga verder om je eerste takenlijst te maken.',
                    'action' => 'continue_users',
                ],
            ];
        }

        return [
            [
                'target' => '[data-onboarding-target="add-user"]',
                'title' => 'Voeg je eerste medewerker toe',
                'body' => 'Medewerkers vullen later takenlijsten in. Klik hier om een account aan te maken.',
                'placement' => 'left',
                'clickTarget' => true,
                'highlightPad' => 6,
                'cta' => 'Klik op Gebruiker toevoegen.',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function listCreateTourSlides(Company $company, ?string $routeName): array
    {
        if ($routeName === 'admin.templates.index') {
            return [
                [
                    'target' => '[data-onboarding-target="templates-grid"]',
                    'title' => 'Kies je template',
                    'body' => 'Blader door de templates en kies zelf welke het beste bij je bedrijf past. Klik op Lijst maken bij het template dat je wilt. Liever helemaal zelf beginnen? Dat kan ook — klik op Toch een eigen lijst maken in dit venster.',
                    'placement' => 'left',
                    'clickTarget' => false,
                    'waitForTarget' => true,
                    'showCustomListOption' => true,
                    'cta' => 'Klik op Lijst maken bij een template naar keuze.',
                ],
            ];
        }

        if ($routeName !== 'admin.lists.create') {
            return [];
        }

        $fromTemplate = $company->onboarding_list_mode === 'template' || request()->filled('template_id');

        $slides = [
            [
                'target' => '[data-onboarding-target="list-basics"]',
                'title' => 'Basisgegevens van je lijst',
                'body' => $fromTemplate
                    ? 'De titel is al ingevuld vanuit je template. Pas de naam aan indien nodig. De beschrijving helpt medewerkers begrijpen waarvoor de lijst dient.'
                    : 'Geef je lijst een duidelijke titel. Beschrijving en categorie zijn optioneel, maar helpen je team de lijst sneller te herkennen.',
                'placement' => 'left',
                'clickTarget' => false,
                'cta' => 'Controleer titel en beschrijving in het gemarkeerde blok.',
            ],
        ];

        if ($fromTemplate) {
            $slides[] = [
                'target' => '[data-onboarding-target="list-template-info"]',
                'title' => 'Taken uit je template',
                'body' => 'Het gekozen template kopieert automatisch alle taken naar deze lijst. Je hoeft ze niet handmatig toe te voegen — na aanmaken kun je ze nog bewerken.',
                'placement' => 'left',
                'clickTarget' => false,
                'cta' => 'Je template staat al geselecteerd hieronder.',
            ];
        }

        $slides[] = [
            'target' => '[data-onboarding-target="list-settings"]',
            'title' => 'Planning en instellingen',
            'body' => 'Kies de prioriteit en hoe vaak de lijst herhaald wordt (bijv. dagelijks of wekelijks). Locatie is optioneel — handig als je meerdere vestigingen hebt.',
            'placement' => 'left',
            'clickTarget' => false,
            'cta' => 'Stel minimaal prioriteit en herhaling in.',
        ];

        $slides[] = [
            'target' => '[data-onboarding-target="list-save"]',
            'title' => 'Maak je lijst aan',
            'body' => 'Als alles klopt, klik op Lijst aanmaken. Daarna wijs je de lijst toe aan een medewerker zodat hij hem kan invullen.',
            'placement' => 'top',
            'clickTarget' => true,
            'highlightPad' => 8,
            'cta' => 'Klik op de blauwe knop Lijst aanmaken.',
        ];

        return $slides;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function companyCalendarTourSlides(): array
    {
        return [
            [
                'target' => '[data-onboarding-target="calendar-view-switch"]',
                'title' => 'Week, dag of maand',
                'body' => 'Schakel tussen week-, dag- en maandweergave. In dagweergave zie je lijsten op een uurrooster; in maandweergave het hele overzicht.',
                'placement' => 'bottom',
                'clickTarget' => false,
                'cta' => 'Kies de weergave die het best past.',
            ],
            [
                'target' => '[data-onboarding-target="calendar-main"]',
                'title' => 'Alle lijsten in één agenda',
                'body' => 'Hier zie je welke takenlijsten gepland staan. Klik op een lijst om taken te beheren. Filter eventueel op locatie bovenaan de pagina.',
                'placement' => 'left',
                'clickTarget' => false,
                'cta' => 'Bekijk het gemarkeerde agenda-overzicht.',
            ],
            [
                'target' => '[data-onboarding-target="calendar-schedule-grid"]',
                'title' => 'Lijsten op tijd plannen',
                'body' => 'In week- of dagweergave kun je klikken of slepen in het tijdschema om een lijst aan een tijdslot te koppelen. Lijsten zonder vaste tijd staan op de rij Hele dag.',
                'placement' => 'left',
                'clickTarget' => false,
                'cta' => 'Open week- of dagweergave om het tijdschema te gebruiken.',
            ],
            [
                'target' => '[data-onboarding-target="calendar-add-list"]',
                'title' => 'Nieuwe lijst toevoegen',
                'body' => 'Via Lijst maak je snel een extra takenlijst aan en plan je die daarna in de agenda.',
                'placement' => 'left',
                'clickTarget' => false,
                'cta' => 'Klik op Lijst om een nieuwe takenlijst te maken.',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function listShowTourSlides(?string $routeName): array
    {
        if ($routeName !== 'admin.lists.show') {
            return [
                [
                    'target' => '[data-onboarding-target="assign-list"]',
                    'title' => 'Koppel lijst aan medewerker',
                    'body' => 'Wijs de lijst toe zodat je medewerker hem kan invullen in de app.',
                    'placement' => 'left',
                    'clickTarget' => true,
                    'highlightPad' => 8,
                    'cta' => 'Klik op Lijst toewijzen.',
                ],
            ];
        }

        return [
            [
                'target' => '[data-onboarding-target="list-tasks"]',
                'title' => 'Agenda van je lijst',
                'body' => 'Je lijst heeft een ingebouwde agenda. Geplande dagen en tijden zie je hier; in dagweergave staan taken van die dag eronder.',
                'placement' => 'left',
                'clickTarget' => false,
                'cta' => 'Bekijk het gemarkeerde agenda-blok.',
            ],
            [
                'target' => '[data-onboarding-target="calendar-view-switch"]',
                'title' => 'Week, dag of maand',
                'body' => 'Week toont alle dagen van de week. Dag toont een uurrooster met tijdsloten. Maand geeft een langere planning.',
                'placement' => 'bottom',
                'clickTarget' => false,
                'cta' => 'Wissel van weergave via Week, Dag of Maand.',
            ],
            [
                'target' => '[data-onboarding-target="list-add-task"]',
                'title' => 'Taak toevoegen',
                'body' => 'Klik op Taak om een nieuwe stap toe te voegen via het pop-up venster. Je hoeft de pagina niet te verlaten.',
                'placement' => 'left',
                'clickTarget' => false,
                'cta' => 'Optioneel: voeg extra taken toe.',
            ],
            [
                'target' => '[data-onboarding-target="list-assignments"]',
                'title' => 'Medewerker toewijzen',
                'body' => 'Je lijst is klaar. Wijs hem toe aan een medewerker — pas dan kan hij de checklist invullen in de app.',
                'placement' => 'left',
                'clickTarget' => false,
                'cta' => 'Ga naar het toewijzingsblok hieronder.',
            ],
            [
                'target' => '[data-onboarding-target="assign-list"]',
                'title' => 'Lijst toewijzen',
                'body' => 'Klik op Lijst toewijzen om een medewerker te kiezen die deze lijst gaat uitvoeren.',
                'placement' => 'top',
                'clickTarget' => true,
                'highlightPad' => 8,
                'cta' => 'Klik op de blauwe knop Lijst toewijzen.',
            ],
            [
                'target' => '[data-onboarding-target="assign-user-field"]',
                'title' => 'Kies een medewerker',
                'body' => 'Selecteer de medewerker die je eerder hebt aangemaakt. De startdatum staat standaard op vandaag — dat kun je aanpassen indien nodig.',
                'placement' => 'left',
                'clickTarget' => false,
                'waitForTarget' => true,
                'cta' => 'Kies een medewerker in het dropdown-menu.',
            ],
            [
                'target' => '[data-onboarding-target="assign-save"]',
                'title' => 'Bevestig de toewijzing',
                'body' => 'Klik op Toewijzen om af te ronden. Je medewerker ziet de lijst daarna in de app — en je account is volledig ingesteld!',
                'placement' => 'top',
                'clickTarget' => true,
                'highlightPad' => 8,
                'cta' => 'Klik op Toewijzen om de onboarding te voltooien.',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     * @deprecated Use listShowTourSlides()
     */
    private function assignListTourSlides(?string $routeName): array
    {
        return $this->listShowTourSlides($routeName);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildTour(string $step, int $employeeCount, Company $company, ?string $routeName = null): array
    {
        $slides = match ($step) {
            Company::ONBOARDING_STEP_WELCOME => [
                [
                    'target' => null,
                    'title' => 'Welkom bij TaskCheck',
                    'body' => 'In 5 korte stappen stel je je organisatie, team en eerste takenlijst in. We wijzen je steeds aan waar je moet klikken.',
                    'action' => 'start',
                ],
            ],
            Company::ONBOARDING_STEP_ORGANIZATION => [
                [
                    'target' => '[data-onboarding-target="org-fields"]',
                    'title' => 'Vul je bedrijfsgegevens in',
                    'body' => 'Organisatienaam, adres, telefoon en e-mail zijn verplicht voor facturen en je account.',
                    'placement' => 'left',
                    'clickTarget' => false,
                    'cta' => 'Vul de velden in het gemarkeerde blok in.',
                ],
                [
                    'target' => '[data-onboarding-target="org-save"]',
                    'title' => 'Sla je gegevens op',
                    'body' => 'Als alles klopt, klik op Opslaan om door te gaan naar medewerkers toevoegen.',
                    'placement' => 'top',
                    'clickTarget' => true,
                    'highlightPad' => 8,
                    'cta' => 'Klik op de blauwe Opslaan-knop.',
                ],
            ],
            Company::ONBOARDING_STEP_USERS => $this->usersTourSlides($employeeCount, $routeName),
            Company::ONBOARDING_STEP_LIST_CHOICE => [
                [
                    'target' => null,
                    'title' => 'Je eerste takenlijst',
                    'body' => 'Snel starten met een kant-en-klaar template, of zelf een lijst opbouwen? Kies hieronder.',
                    'action' => 'list_choice',
                ],
            ],
            Company::ONBOARDING_STEP_LIST_CREATE => $this->listCreateTourSlides($company, $routeName),
            Company::ONBOARDING_STEP_ASSIGN => $this->assignListTourSlides($routeName),
            default => [],
        };

        return [
            'step' => $step,
            'step_number' => $this->stepNumber($step),
            'total_steps' => 5,
            'slides' => array_values($slides),
            'routes' => [
                'start' => route('admin.onboarding.start'),
                'continue_users' => route('admin.onboarding.users.continue'),
                'list_choice' => route('admin.onboarding.list-choice'),
            ],
            'can_continue_users' => $employeeCount >= 1,
        ];
    }

    private function stepNumber(string $step): int
    {
        return match ($step) {
            Company::ONBOARDING_STEP_WELCOME,
            Company::ONBOARDING_STEP_ORGANIZATION => 1,
            Company::ONBOARDING_STEP_USERS => 2,
            Company::ONBOARDING_STEP_LIST_CHOICE => 3,
            Company::ONBOARDING_STEP_LIST_CREATE => 4,
            Company::ONBOARDING_STEP_ASSIGN => 5,
            default => 1,
        };
    }

    public function redirectRoute(Company $company): string
    {
        $step = $company->onboarding_step === 'lists'
            ? Company::ONBOARDING_STEP_LIST_CHOICE
            : $company->onboarding_step;

        return match ($step) {
            Company::ONBOARDING_STEP_ORGANIZATION => 'admin.settings.edit',
            Company::ONBOARDING_STEP_USERS,
            Company::ONBOARDING_STEP_LIST_CHOICE => 'admin.users.index',
            Company::ONBOARDING_STEP_LIST_CREATE => $company->onboarding_list_mode === 'custom'
                ? 'admin.lists.create'
                : 'admin.templates.index',
            Company::ONBOARDING_STEP_ASSIGN => $company->onboarding_list_id
                ? 'admin.lists.show'
                : ($company->onboarding_list_mode === 'custom' ? 'admin.lists.create' : 'admin.templates.index'),
            default => 'admin.dashboard',
        };
    }

    /**
     * @return array<int, string>
     */
    public function redirectRouteParameters(Company $company): array
    {
        $step = $company->onboarding_step === 'lists'
            ? Company::ONBOARDING_STEP_LIST_CHOICE
            : $company->onboarding_step;

        if ($step === Company::ONBOARDING_STEP_ASSIGN && $company->onboarding_list_id) {
            return ['list' => $company->onboarding_list_id];
        }

        return [];
    }

    public function routeAllowedDuringOnboarding(string $routeName, Company $company): bool
    {
        if (str_starts_with($routeName, 'admin.onboarding.')) {
            return true;
        }

        $step = $company->onboarding_step === 'lists'
            ? Company::ONBOARDING_STEP_LIST_CHOICE
            : $company->onboarding_step;

        $allowed = match ($step) {
            Company::ONBOARDING_STEP_WELCOME => [
                'admin.dashboard',
            ],
            Company::ONBOARDING_STEP_ORGANIZATION => [
                'admin.settings.edit',
                'admin.settings.update',
            ],
            Company::ONBOARDING_STEP_USERS => [
                'admin.users.index',
                'admin.users.create',
                'admin.users.store',
            ],
            Company::ONBOARDING_STEP_LIST_CHOICE => [
                'admin.users.index',
                'admin.users.create',
                'admin.users.store',
            ],
            Company::ONBOARDING_STEP_LIST_CREATE => [
                'admin.templates.index',
                'admin.templates.show',
                'admin.templates.create-list',
                'admin.templates.global.import',
                'admin.lists.create',
                'admin.lists.store',
            ],
            Company::ONBOARDING_STEP_ASSIGN => [
                'admin.lists.show',
                'admin.lists.assign',
            ],
            default => [],
        };

        foreach ($allowed as $pattern) {
            if ($routeName === $pattern) {
                return true;
            }
        }

        return false;
    }

    public function hasRequiredOrganizationDetails(Company $company): bool
    {
        foreach (['name', 'address', 'phone', 'email'] as $field) {
            if (trim((string) $company->{$field}) === '') {
                return false;
            }
        }

        return true;
    }

    public function handleOrganizationSaved(Company $company): void
    {
        if (!$company->needsOnboarding()) {
            return;
        }

        if ($company->onboarding_step !== Company::ONBOARDING_STEP_ORGANIZATION) {
            return;
        }

        if (!$this->hasRequiredOrganizationDetails($company)) {
            return;
        }

        $company->advanceOnboardingTo(Company::ONBOARDING_STEP_USERS);
    }

    public function handleListCreated(Company $company, TaskList $list): void
    {
        if (!$company->needsOnboarding() || $company->onboarding_step !== Company::ONBOARDING_STEP_LIST_CREATE) {
            return;
        }

        $company->update([
            'onboarding_step' => Company::ONBOARDING_STEP_ASSIGN,
            'onboarding_list_id' => $list->id,
        ]);
    }

    public function handleAssignmentCreated(Company $company, int $listId): void
    {
        if (!$company->needsOnboarding() || $company->onboarding_step !== Company::ONBOARDING_STEP_ASSIGN) {
            return;
        }

        if ((int) $company->onboarding_list_id !== $listId) {
            return;
        }

        $hasUserAssignment = ListAssignment::query()
            ->where('list_id', $listId)
            ->where('is_active', true)
            ->whereNotNull('user_id')
            ->exists();

        if (!$hasUserAssignment) {
            return;
        }

        $company->completeOnboarding();
    }
}
