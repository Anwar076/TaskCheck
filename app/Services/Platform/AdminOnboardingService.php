<?php

namespace App\Services\Platform;

use App\Models\Organisation\Company;
use App\Models\Checklist\ListAssignment;
use App\Models\Checklist\TaskList;
use App\Models\Organisation\User;

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
            'total_steps' => 6,
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

        if ($routeName === 'admin.dashboard') {
            return $this->dashboardHelpSlides();
        }

        if ($routeName === 'admin.live-monitoring') {
            return $this->liveMonitoringHelpSlides();
        }

        if ($routeName === 'admin.weekly-overview') {
            return $this->weeklyOverviewHelpSlides();
        }

        if ($routeName === 'admin.lists.index') {
            return $this->listsIndexHelpSlides();
        }

        if ($routeName === 'admin.lists.calendar') {
            return $this->stripHelpSlides($this->companyCalendarTourSlides());
        }

        if ($routeName === 'admin.lists.create') {
            return $this->stripHelpSlides($this->listCreateTourSlides($company, 'admin.lists.create'));
        }

        if ($routeName === 'admin.lists.show') {
            return $this->stripHelpSlides($this->listShowTourSlides('admin.lists.show'));
        }

        if ($routeName === 'admin.lists.edit') {
            return $this->listEditHelpSlides();
        }

        if ($routeName === 'admin.lists.ai-import') {
            return $this->aiImportHelpSlides();
        }

        if ($routeName === 'admin.starter-packs.index') {
            return $this->stripHelpSlides($this->starterPackTourSlides());
        }

        if ($routeName === 'admin.templates.index') {
            return $this->stripHelpSlides($this->listCreateTourSlides($company, 'admin.templates.index'));
        }

        if (str_starts_with($routeName, 'admin.settings')) {
            return $this->stripHelpSlides($this->organizationTourSlides());
        }

        if ($routeName === 'admin.users.index') {
            return $this->usersIndexHelpSlides($employeeCount);
        }

        if ($routeName === 'admin.users.create') {
            return $this->stripHelpSlides($this->usersTourSlides($employeeCount, $routeName));
        }

        if (str_starts_with($routeName, 'admin.users')) {
            return $this->userDetailHelpSlides($routeName);
        }

        if (str_starts_with($routeName, 'admin.submissions')) {
            return $this->submissionsHelpSlides($routeName);
        }

        if (str_starts_with($routeName, 'admin.notifications')) {
            return $this->notificationsHelpSlides($routeName);
        }

        if (str_starts_with($routeName, 'admin.locations')) {
            return $this->locationsHelpSlides($routeName);
        }

        if (str_starts_with($routeName, 'admin.templates')) {
            return $this->templatesHelpSlides($routeName, $company);
        }

        if (str_starts_with($routeName, 'admin.tasks')) {
            return $this->tasksHelpSlides($routeName);
        }

        if (str_starts_with($routeName, 'admin.lists')) {
            return $this->listsMiscHelpSlides($routeName);
        }

        return [$this->helpCenterSlide(
            'Hulp bij TaskCheck',
            'Gebruik het menu links om tussen onderdelen te wisselen. Op de meeste pagina\'s vind je actieknoppen rechtsboven — bijvoorbeeld om lijsten toe te wijzen of inzendingen te beoordelen.'
        )];
    }

    /**
     * @return array<string, mixed>
     */
    private function helpCenterSlide(string $title, string $body, ?string $cta = null): array
    {
        $slide = [
            'target' => null,
            'title' => $title,
            'body' => $body,
            'placement' => 'center',
        ];

        if ($cta) {
            $slide['cta'] = $cta;
        }

        return $slide;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function dashboardHelpSlides(): array
    {
        return [
            $this->helpCenterSlide(
                'Dashboard',
                'Je startpagina met een overzicht van vandaag: open lijsten, recente activiteit en teamvoortgang. Alles wat je nodig hebt om snel te zien hoe het gaat.',
                'Scroll naar Teamprestaties voor live voortgang per medewerker.'
            ),
            $this->helpCenterSlide(
                'Teamprestaties',
                'Hier zie je per medewerker hoeveel lijsten klaar zijn en wie nu bezig is. De voortgang is gebaseerd op taken binnen lijsten, niet alleen op afgeronde checklists.',
                'Klik op Ververs voor de nieuwste cijfers.'
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function liveMonitoringHelpSlides(): array
    {
        return [
            $this->helpCenterSlide(
                'Live monitoring',
                'Volg in realtime welke medewerkers bezig zijn met een checklist. Je ziet op welke lijst ze werken en hoever ze zijn.',
                'Handig om te checken of alles op schema loopt.'
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function weeklyOverviewHelpSlides(): array
    {
        return [
            $this->helpCenterSlide(
                'Weekoverzicht',
                'Analyseer prestaties over een gekozen periode. Filter op locatie en datums om trends, teamresultaten en meest gebruikte lijsten te bekijken.',
                'Gebruik de snelknoppen Deze week of Vorige week om snel te wisselen.'
            ),
            $this->helpCenterSlide(
                'Grafieken en team',
                'De trendgrafiek toont ingediende en afgeronde lijsten per dag. Onderaan zie je prestaties per medewerker en welke lijsten het meest gebruikt worden.',
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function listsIndexHelpSlides(): array
    {
        return [
            $this->helpCenterSlide(
                'Takenlijsten',
                'Hier staan al je checklists. Maak nieuwe lijsten aan, open een lijst om taken te beheren, of wijs lijsten toe aan medewerkers.',
                'Klik op een lijst om taken, planning en toewijzingen te beheren.'
            ),
            $this->helpCenterSlide(
                'Nieuwe lijst',
                'Via Lijst aanmaken start je zelf een lijst. Via Templates kies je een kant-en-klare basis. Via AI-import kun je een bestaand document omzetten naar een lijst.',
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function listEditHelpSlides(): array
    {
        return [
            $this->helpCenterSlide(
                'Lijst bewerken',
                'Pas titel, beschrijving, planning en opties aan. Wijzigingen zijn zichtbaar voor medewerkers zodra je opslaat en de lijst actief is.',
                'Scroll naar tijdslots om vaste momenten in de agenda te plannen.'
            ),
            $this->helpCenterSlide(
                'Taken en toewijzing',
                'Taken beheer je op de lijstpagina (niet hier). Toewijzingen aan medewerkers stel je in via Lijst toewijzen op het overzicht van de lijst.',
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function aiImportHelpSlides(): array
    {
        return [
            $this->helpCenterSlide(
                'AI-import',
                'Upload een PDF, foto, Excel of Word-document — of beschrijf kort wat je nodig hebt. TaskCheck zet dit om naar een takenlijst met stappen en bewijsvelden.',
                'Controleer de gegenereerde lijst altijd voordat je opslaat.'
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function usersIndexHelpSlides(int $employeeCount): array
    {
        return [
            $this->clickTourSlide([
                'target' => '[data-onboarding-target="add-user"]',
                'title' => 'Medewerkers beheren',
                'body' => 'Hier zie je alle accounts in je organisatie. Medewerkers vullen later checklists in via de app of het medewerkerportaal.',
                'placement' => 'left',
                'cta' => $employeeCount > 0
                    ? 'Je hebt ' . $employeeCount . ' medewerker(s). Voeg er gerust meer toe via Gebruiker toevoegen.'
                    : 'Klik op Gebruiker toevoegen om je eerste medewerker aan te maken.',
            ]),
            $this->helpCenterSlide(
                'Rollen',
                'Medewerkers zien alleen hun toegewezen lijsten. Admins beheren instellingen, lijsten en inzendingen. Wijs lijsten toe via de lijstpagina, niet via dit scherm.',
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function userDetailHelpSlides(string $routeName): array
    {
        $isEdit = str_contains($routeName, 'edit');

        return [
            $this->helpCenterSlide(
                $isEdit ? 'Medewerker bewerken' : 'Medewerker bekijken',
                $isEdit
                    ? 'Pas naam, e-mail, afdeling of locatie aan. De medewerker behoudt toegang zolang het account actief is.'
                    : 'Bekijk gegevens en activiteit van deze medewerker. Open een inzending om details of bewijs te controleren.',
                'Wijs lijsten toe via Takenlijsten → lijst openen → Lijst toewijzen.'
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function submissionsHelpSlides(string $routeName): array
    {
        if ($routeName === 'admin.submissions.show') {
            return [
                $this->helpCenterSlide(
                    'Inzending beoordelen',
                    'Bekijk per taak het bewijs van de medewerker: foto\'s, video\'s, notities en handtekeningen. Keur taken goed of af, of vraag om opnieuw uit te voeren.',
                    'Goedgekeurde taken tellen mee als voltooid. Bij afkeur krijgt de medewerker een melding.'
                ),
                $this->helpCenterSlide(
                    'Checklist afronden',
                    'Als alle taken zijn beoordeeld, rond je de hele inzending af. De status wordt dan Goedgekeurd of Afgewezen afhankelijk van de taken.',
                ),
            ];
        }

        return [
            $this->helpCenterSlide(
                'Inzendingen',
                'Overzicht van ingediende checklists. Filter op status om te zien wat wacht op beoordeling, goedgekeurd is of opnieuw moet.',
                'Klik op een rij om de details en bewijsbestanden te openen.'
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function notificationsHelpSlides(string $routeName): array
    {
        if ($routeName === 'admin.notifications.create') {
            return [
                $this->helpCenterSlide(
                    'Melding versturen',
                    'Stuur een bericht naar je team — bijvoorbeeld een reminder of belangrijke wijziging. Medewerkers zien dit in hun app en op het dashboard.',
                    'Houd het kort en duidelijk; voeg een link toe als dat helpt.'
                ),
            ];
        }

        return [
            $this->helpCenterSlide(
                'Meldingen',
                'Alle systeem- en teammeldingen op één plek. Markeer als gelezen om je inbox op orde te houden.',
                'Via Melding maken stuur je zelf een bericht naar medewerkers.'
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function locationsHelpSlides(string $routeName): array
    {
        $isCreate = str_contains($routeName, 'create');
        $isEdit = str_contains($routeName, 'edit');

        return [
            $this->helpCenterSlide(
                $isCreate ? 'Locatie toevoegen' : ($isEdit ? 'Locatie bewerken' : 'Locaties'),
                $isCreate || $isEdit
                    ? 'Geef de locatie een herkenbare naam. Koppel lijsten en medewerkers aan een locatie om te filteren in agenda en rapportages.'
                    : 'Beheer vestigingen of afdelingen. Locaties helpen lijsten, medewerkers en rapportages te scheiden.',
                'Medewerkers en lijsten kunnen optioneel aan één locatie gekoppeld worden.'
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function templatesHelpSlides(string $routeName, Company $company): array
    {
        if ($routeName === 'admin.templates.index') {
            return $this->stripHelpSlides($this->listCreateTourSlides($company, 'admin.templates.index'));
        }

        if (str_contains($routeName, 'create') || str_contains($routeName, 'edit')) {
            return [
                $this->helpCenterSlide(
                    str_contains($routeName, 'create') ? 'Template aanmaken' : 'Template bewerken',
                    'Templates zijn herbruikbare blauwdrukken voor takenlijsten. Voeg taken toe met bewijstype (foto, handtekening, tekst). Maak daarna via Lijst maken een echte lijst voor je team.',
                ),
            ];
        }

        return [
            $this->helpCenterSlide(
                'Template bekijken',
                'Bekijk welke taken in dit template zitten. Gebruik Lijst maken om er direct een actieve takenlijst van te maken voor je organisatie.',
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function tasksHelpSlides(string $routeName): array
    {
        return [
            $this->helpCenterSlide(
                'Taak beheren',
                'Elke taak kan verplicht zijn en bewijs vereisen (foto, video, tekst of handtekening). Medewerkers moeten dit invullen voordat ze de taak als klaar markeren.',
                'Open de lijstpagina om taken toe te voegen of te herschikken.'
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function listsMiscHelpSlides(string $routeName): array
    {
        return [
            $this->helpCenterSlide(
                'Takenlijst',
                'Beheer je checklist: taken, planning in de agenda, en toewijzing aan medewerkers. Alleen toegewezen medewerkers zien de lijst in hun app.',
                'Tip: open Week- of Dagweergave op de lijstpagina om tijdslots in te plannen.'
            ),
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
     * @param array<string, mixed> $slide
     * @return array<string, mixed>
     */
    private function formTourSlide(array $slide): array
    {
        return array_merge([
            'clickTarget' => false,
            'allowScroll' => true,
            'highlightFullTarget' => true,
            'highlightAnchor' => 'top',
            'scrollBlock' => 'start',
            'highlightPad' => 12,
        ], $slide);
    }

    /**
     * @param array<string, mixed> $slide
     * @return array<string, mixed>
     */
    private function clickTourSlide(array $slide): array
    {
        return array_merge([
            'clickTarget' => true,
            'highlightPad' => 10,
            'scrollBlock' => 'center',
        ], $slide);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function organizationTourSlides(bool $onboarding = false): array
    {
        return [
            $this->formTourSlide([
                'target' => '[data-onboarding-target="org-profile"]',
                'title' => $onboarding ? 'Organisatieprofiel' : 'Logo en naam',
                'body' => $onboarding
                    ? 'Upload je logo en vul je organisatienaam in.'
                    : 'Beheer het logo en de naam van je organisatie.',
                'placement' => 'left',
                'cta' => 'Vul logo en organisatienaam in.',
            ]),
            $this->formTourSlide([
                'target' => '[data-onboarding-target="org-contact"]',
                'title' => 'Contactgegevens',
                'body' => $onboarding
                    ? 'Adres, telefoon en e-mail zijn verplicht voor facturen en je account.'
                    : 'Hier beheer je adres, telefoon en e-mail van je organisatie.',
                'placement' => 'left',
                'cta' => 'Vul de contactvelden in het gemarkeerde blok in.',
            ]),
            $this->formTourSlide([
                'target' => '[data-onboarding-target="org-working-hours"]',
                'title' => 'Werktijden voor de agenda',
                'body' => 'Stel per dag in wanneer je open bent. De agenda toont alleen deze uren — zo plan je takenlijsten op de juiste momenten.',
                'placement' => 'left',
                'cta' => 'Pas start- en eindtijd aan per dag indien nodig.',
            ]),
            $this->formTourSlide([
                'target' => '[data-onboarding-target="org-reporting"]',
                'title' => 'Rapportage via e-mail',
                'body' => $onboarding
                    ? 'Optioneel: ontvang dagelijks of wekelijks een samenvatting per e-mail op het adres bij je contactgegevens.'
                    : 'Stel in of je automatisch rapportages per e-mail wilt ontvangen.',
                'placement' => 'left',
                'cta' => $onboarding
                    ? 'Zet het aan als je dit wilt; je kunt het later altijd wijzigen.'
                    : 'Kies frequentie en tijdstip voor je rapportages.',
            ]),
            $this->clickTourSlide([
                'target' => '[data-onboarding-target="org-save"]',
                'title' => $onboarding ? 'Sla je gegevens op' : 'Gegevens opslaan',
                'body' => $onboarding
                    ? 'Controleer je gegevens en werktijden. Klik op Opslaan om door te gaan naar medewerkers toevoegen.'
                    : 'Vergeet niet op Opslaan te klikken na wijzigingen.',
                'placement' => 'top',
                'cta' => 'Klik op Opslaan om te bevestigen.',
            ]),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function usersTourSlides(int $employeeCount, ?string $routeName): array
    {
        if ($routeName === 'admin.users.create') {
            return [
                $this->formTourSlide([
                    'target' => '[data-onboarding-target="user-basics"]',
                    'title' => 'Naam en e-mail',
                    'body' => 'Vul de naam en het e-mailadres in. De medewerker ontvangt daarna een uitnodiging om zelf een wachtwoord in te stellen.',
                    'placement' => 'left',
                    'cta' => 'Vul naam en e-mail in het gemarkeerde blok in.',
                ]),
                $this->formTourSlide([
                    'target' => '[data-onboarding-target="user-role"]',
                    'title' => 'Rol en afdeling',
                    'body' => 'Rol staat standaard op Medewerker — dat is meestal wat je nodig hebt. Afdeling en locatie zijn optioneel.',
                    'placement' => 'left',
                    'cta' => 'Controleer de rol en kies eventueel een afdeling.',
                ]),
                $this->clickTourSlide([
                    'target' => '[data-onboarding-target="user-save"]',
                    'title' => 'Maak de medewerker aan',
                    'body' => 'Als alles klopt, klik op Gebruiker aanmaken om het account op te slaan.',
                    'placement' => 'top',
                    'cta' => 'Klik op de blauwe knop Gebruiker aanmaken.',
                ]),
            ];
        }

        if ($employeeCount >= 1) {
            $justCreated = session()->pull('onboarding_user_created', false);

            return [
                [
                    'target' => null,
                    'title' => $justCreated ? 'Medewerker aangemaakt' : 'Nog een medewerker toevoegen?',
                    'body' => $justCreated
                        ? 'De medewerker ontvangt een uitnodiging per e-mail. Wil je nog iemand toevoegen, of ga je verder met je eerste takenlijst?'
                        : 'Je hebt al ' . $employeeCount . ' medewerker(s). Wil je er nog een toevoegen, of ga je verder met de onboarding?',
                    'action' => 'users_more_choice',
                    'placement' => 'center',
                    'cta' => 'Kies hieronder wat je wilt doen.',
                ],
            ];
        }

        return [
            $this->clickTourSlide([
                'target' => '[data-onboarding-target="add-user"]',
                'title' => 'Voeg je eerste medewerker toe',
                'body' => 'Medewerkers vullen later takenlijsten in. Klik hier om een account aan te maken.',
                'placement' => 'left',
                'highlightPad' => 8,
                'cta' => 'Klik op Gebruiker toevoegen.',
            ]),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function starterPackTourSlides(bool $onboarding = false): array
    {
        return [
            [
                'target' => null,
                'title' => $onboarding ? 'Compliance starterpacks' : 'Starterpacks',
                'body' => $onboarding
                    ? 'Horeca en food branches hebben kant-en-klare compliance-controlelijsten. Kies een starterpack en bepaal daarna zelf welke templates je wilt toevoegen — HACCP, temperatuur, hygiëne en meer.'
                    : 'Activeer een branche-pakket om kant-en-klare compliance-templates te importeren. Je kiest bij het activeren zelf welke controlelijsten meegaan.',
                'placement' => 'center',
                'cta' => $onboarding ? 'Lees even door, daarna kies je een pack of ga verder.' : null,
            ],
            $this->formTourSlide([
                'target' => '[data-onboarding-target="starter-packs-section"]',
                'title' => 'Kies je branche',
                'body' => 'Elk starterpack bevat kant-en-klare controlelijsten voor jouw sector. Gebruik de uitklapper om alle inbegrepen lijsten te bekijken en klik op Starterpack activeren bij het pakket dat bij je bedrijf past.',
                'placement' => 'bottom',
                'allowScroll' => true,
                'highlightPad' => 12,
                'scrollBlock' => 'start',
                'cta' => 'Activeer optioneel een pack, of sla deze stap over en ga door.',
            ]),
            [
                'target' => null,
                'title' => 'Controlelijsten kiezen',
                'body' => 'Na het klikken op Starterpack activeren opent een popup. Daar staan alle controlelijsten standaard aangevinkt. Vink uit wat je niet nodig hebt, of gebruik Alles aanvinken en Alles uitvinken om snel te starten.',
                'placement' => 'center',
                'cta' => 'Activeer alleen de controlelijsten die je echt wilt gebruiken.',
            ],
            [
                'target' => null,
                'title' => 'Klaar met starterpacks?',
                'body' => 'Heb je een pack geactiveerd? Dan staan alleen de gekozen templates klaar. Geen pack nodig? Geen probleem. In de volgende stap kies je hoe je je eerste takenlijst maakt.',
                'action' => 'continue_starter_pack',
                'placement' => 'center',
                'cta' => 'Klik op Doorgaan om verder te gaan.',
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
                $this->formTourSlide([
                    'target' => '[data-onboarding-target="templates-grid"]',
                    'title' => 'Kies je template',
                    'body' => 'Blader door je templates en kies welke het beste bij je bedrijf past. Heb je een starterpack geactiveerd? Dan staan die controlelijsten hier al klaar. Klik op Lijst maken bij het template dat je wilt.',
                    'placement' => 'left',
                    'waitForTarget' => true,
                    'showCustomListOption' => true,
                    'cta' => 'Klik op Lijst maken bij een template naar keuze.',
                ]),
            ];
        }

        if ($routeName !== 'admin.lists.create') {
            return [];
        }

        $fromTemplate = $company->onboarding_list_mode === 'template' || request()->filled('template_id');

        $slides = [
            $this->formTourSlide([
                'target' => '[data-onboarding-target="list-basics"]',
                'title' => 'Basisgegevens van je lijst',
                'body' => $fromTemplate
                    ? 'De titel is al ingevuld vanuit je template. Pas de naam aan indien nodig. De beschrijving helpt medewerkers begrijpen waarvoor de lijst dient.'
                    : 'Geef je lijst een duidelijke titel. Beschrijving en categorie zijn optioneel, maar helpen je team de lijst sneller te herkennen.',
                'placement' => 'left',
                'cta' => 'Controleer titel en beschrijving in het gemarkeerde blok.',
            ]),
        ];

        if ($fromTemplate) {
            $slides[] = $this->formTourSlide([
                'target' => '[data-onboarding-target="list-template-info"]',
                'title' => 'Taken uit je template',
                'body' => 'Het gekozen template kopieert automatisch alle taken naar deze lijst. Je hoeft ze niet handmatig toe te voegen — na aanmaken kun je ze nog bewerken.',
                'placement' => 'left',
                'cta' => 'Je template staat al geselecteerd hieronder.',
            ]);
        }

        $slides[] = $this->formTourSlide([
            'target' => '[data-onboarding-target="list-settings"]',
            'title' => 'Planning en instellingen',
            'body' => 'Kies de prioriteit, herhaling (bijv. dagelijks of wekelijks) en optioneel een locatie.',
            'placement' => 'left',
            'cta' => 'Stel minimaal prioriteit en herhaling in.',
        ]);

        $slides[] = $this->formTourSlide([
            'target' => '[data-onboarding-target="list-time-slots"]',
            'title' => 'Tijdslots in de agenda',
            'body' => 'Optioneel: koppel vaste tijden aan weekdagen. Zo verschijnt de lijst automatisch op het juiste moment in de agenda.',
            'placement' => 'left',
            'cta' => 'Stel tijdslots in of sla deze stap over.',
        ]);

        $slides[] = $this->formTourSlide([
            'target' => '[data-onboarding-target="list-extra-options"]',
            'title' => 'Extra opties',
            'body' => 'Digitale handtekening: de medewerker moet tekenen wanneer de lijst is afgerond. Actief: alleen aangevinkte lijsten zijn zichtbaar en uitvoerbaar in de app voor medewerkers.',
            'placement' => 'left',
            'cta' => 'Vink opties aan of uit waar nodig.',
        ]);

        $slides[] = $this->clickTourSlide([
            'target' => '[data-onboarding-target="list-save"]',
            'title' => 'Maak je lijst aan',
            'body' => 'Als alles klopt, klik op Lijst aanmaken. Daarna wijs je de lijst toe aan een medewerker zodat hij hem kan invullen.',
            'placement' => 'top',
            'cta' => 'Klik op de blauwe knop Lijst aanmaken.',
        ]);

        return $slides;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function companyCalendarTourSlides(): array
    {
        return [
            $this->formTourSlide([
                'target' => '[data-onboarding-target="calendar-view-switch"]',
                'title' => 'Week, dag of maand',
                'body' => 'Schakel tussen week-, dag- en maandweergave. In dagweergave zie je lijsten op een uurrooster; in maandweergave het hele overzicht.',
                'placement' => 'bottom',
                'allowScroll' => false,
                'highlightFullTarget' => false,
                'highlightPad' => 8,
                'cta' => 'Kies de weergave die het best past.',
            ]),
            $this->formTourSlide([
                'target' => '[data-onboarding-target="calendar-toolbar"]',
                'title' => 'Alle lijsten in één agenda',
                'body' => 'Hier zie je welke takenlijsten gepland staan. Klik op een lijst om taken te beheren. Filter eventueel op locatie bovenaan de pagina.',
                'placement' => 'bottom',
                'allowScroll' => false,
                'highlightFullTarget' => false,
                'highlightPad' => 8,
                'cta' => 'Gebruik de knoppen boven de agenda om te navigeren.',
            ]),
            $this->formTourSlide([
                'target' => '[data-onboarding-target="calendar-schedule-help"]',
                'title' => 'Lijsten op tijd plannen',
                'body' => 'In week- of dagweergave kun je klikken of slepen in het tijdschema om een lijst aan een tijdslot te koppelen. Lijsten zonder vaste tijd staan op de rij Hele dag.',
                'placement' => 'bottom',
                'cta' => 'Open week- of dagweergave om het tijdschema te gebruiken.',
            ]),
            $this->formTourSlide([
                'target' => '[data-onboarding-target="calendar-add-list"]',
                'title' => 'Nieuwe lijst toevoegen',
                'body' => 'Via Lijst maak je snel een extra takenlijst aan en plan je die daarna in de agenda.',
                'placement' => 'left',
                'allowScroll' => false,
                'highlightFullTarget' => false,
                'highlightPad' => 8,
                'cta' => 'Klik op Lijst om een nieuwe takenlijst te maken.',
            ]),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function listShowTourSlides(?string $routeName): array
    {
        if ($routeName !== 'admin.lists.show') {
            return [
                $this->clickTourSlide([
                    'target' => '[data-onboarding-target="assign-list"]',
                    'title' => 'Koppel lijst aan medewerker',
                    'body' => 'Wijs de lijst toe zodat je medewerker hem kan invullen in de app.',
                    'placement' => 'left',
                    'cta' => 'Klik op Lijst toewijzen.',
                ]),
            ];
        }

        return [
            $this->formTourSlide([
                'target' => '[data-onboarding-target="list-calendar-toolbar"]',
                'title' => 'Agenda van je lijst',
                'body' => 'Je lijst heeft een ingebouwde agenda. Geplande dagen en tijden zie je hier; in dagweergave staan taken van die dag eronder.',
                'placement' => 'bottom',
                'allowScroll' => false,
                'highlightFullTarget' => false,
                'highlightPad' => 8,
                'cta' => 'Gebruik de kalender bovenaan om de planning te bekijken.',
            ]),
            $this->formTourSlide([
                'target' => '[data-onboarding-target="calendar-view-switch"]',
                'title' => 'Week, dag of maand',
                'body' => 'Week toont alle dagen van de week. Dag toont een uurrooster met tijdsloten. Maand geeft een langere planning.',
                'placement' => 'bottom',
                'allowScroll' => false,
                'highlightFullTarget' => false,
                'highlightPad' => 8,
                'cta' => 'Wissel van weergave via Week, Dag of Maand.',
            ]),
            $this->formTourSlide([
                'target' => '[data-onboarding-target="list-add-task"]',
                'title' => 'Taak toevoegen',
                'body' => 'Klik op Taak om een nieuwe stap toe te voegen via het pop-up venster. Je hoeft de pagina niet te verlaten.',
                'placement' => 'left',
                'allowScroll' => false,
                'highlightFullTarget' => false,
                'highlightPad' => 8,
                'cta' => 'Optioneel: voeg extra taken toe.',
            ]),
            $this->formTourSlide([
                'target' => '[data-onboarding-target="assignment-summary"]',
                'title' => 'Medewerker toewijzen',
                'body' => 'Je lijst is klaar. Wijs hem toe aan een medewerker — pas dan kan hij de checklist invullen in de app.',
                'placement' => 'right',
                'cta' => 'Dit blok toont wie toegang heeft tot deze lijst.',
            ]),
            $this->clickTourSlide([
                'target' => '[data-onboarding-target="assign-list"]',
                'title' => 'Lijst toewijzen',
                'body' => 'Klik op Lijst toewijzen om een medewerker te kiezen die deze lijst gaat uitvoeren.',
                'placement' => 'top',
                'cta' => 'Klik op de blauwe knop Lijst toewijzen.',
            ]),
            $this->formTourSlide([
                'target' => '[data-onboarding-target="assign-user-field"]',
                'title' => 'Kies een medewerker',
                'body' => 'Selecteer de medewerker die je eerder hebt aangemaakt. De startdatum staat standaard op vandaag — dat kun je aanpassen indien nodig.',
                'placement' => 'left',
                'waitForTarget' => true,
                'scrollBlock' => 'center',
                'cta' => 'Kies een medewerker in het dropdown-menu.',
            ]),
            $this->clickTourSlide([
                'target' => '[data-onboarding-target="assign-save"]',
                'title' => 'Bevestig de toewijzing',
                'body' => 'Klik op Toewijzen om af te ronden. Je medewerker ziet de lijst daarna in de app — en je account is volledig ingesteld!',
                'placement' => 'top',
                'waitForTarget' => true,
                'cta' => 'Klik op Toewijzen om de onboarding te voltooien.',
            ]),
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
                    'body' => 'In 6 korte stappen stel je je organisatie, team, starterpack en eerste takenlijst in. We wijzen je steeds aan waar je moet klikken.',
                    'action' => 'start',
                ],
            ],
            Company::ONBOARDING_STEP_ORGANIZATION => $this->organizationTourSlides(true),
            Company::ONBOARDING_STEP_USERS => $this->usersTourSlides($employeeCount, $routeName),
            Company::ONBOARDING_STEP_STARTER_PACK => $this->starterPackTourSlides(true),
            Company::ONBOARDING_STEP_LIST_CHOICE => [
                [
                    'target' => null,
                    'title' => 'Je eerste takenlijst',
                    'body' => 'Snel starten met een template (bijvoorbeeld uit je starterpack), of zelf een lijst opbouwen? Kies hieronder.',
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
            'total_steps' => 6,
            'slides' => array_values($slides),
            'routes' => [
                'start' => route('admin.onboarding.start'),
                'continue_users' => route('admin.onboarding.users.continue'),
                'continue_starter_pack' => route('admin.onboarding.starter-pack.continue'),
                'users_create' => route('admin.users.create'),
                'list_choice' => route('admin.onboarding.list-choice'),
                'skip' => route('admin.onboarding.skip'),
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
            Company::ONBOARDING_STEP_STARTER_PACK => 3,
            Company::ONBOARDING_STEP_LIST_CHOICE => 4,
            Company::ONBOARDING_STEP_LIST_CREATE => 5,
            Company::ONBOARDING_STEP_ASSIGN => 6,
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
            Company::ONBOARDING_STEP_USERS => 'admin.users.index',
            Company::ONBOARDING_STEP_STARTER_PACK => 'admin.starter-packs.index',
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
            Company::ONBOARDING_STEP_STARTER_PACK => [
                'admin.starter-packs.index',
                'admin.starter-packs.activate',
                'admin.starter-packs.deactivate',
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

    public function handleAssignmentCreated(Company $company, int $listId): bool
    {
        if (!$company->needsOnboarding() || $company->onboarding_step !== Company::ONBOARDING_STEP_ASSIGN) {
            return false;
        }

        if ((int) $company->onboarding_list_id !== $listId) {
            return false;
        }

        $hasUserAssignment = ListAssignment::query()
            ->where('list_id', $listId)
            ->where('is_active', true)
            ->whereNotNull('user_id')
            ->exists();

        if (!$hasUserAssignment) {
            return false;
        }

        $company->completeOnboarding();

        return true;
    }
}
