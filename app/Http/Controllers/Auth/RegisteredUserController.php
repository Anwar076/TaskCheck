<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeQuickstartMail;
use App\Models\Organisation\User;
use App\Models\Organisation\Company;
use App\Models\Checklist\TaskTemplate;
use App\Models\Checklist\TemplateTask;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'company_name' => ['required', 'string', 'max:255'],
        ]);

        $trialEnd = Company::trialEndForPlan('starter');
        $trialPlan = Company::plan('starter') ?? [];
        $trialLabel = ($trialPlan['trial_duration_value'] ?? 14).' '.match($trialPlan['trial_duration_unit'] ?? 'days') { 'weeks' => 'weken', 'months' => 'maanden', default => 'dagen' };
        DB::beginTransaction();
        try {
            // Create company with trial period
            $company = Company::create([
                'name' => $request->company_name,
                'company_type' => config('app.default_company_type', 'horeca'),
                'subscription_status' => 'trial',
                'subscription_plan' => 'starter',
                'trial_ends_at' => $trialEnd,
                'billing_period' => 'monthly',
                'billing_start_date' => $trialEnd->toDateString(),
            ]);

            $this->seedDefaultTemplatesForCompany($company);

            // Create user as admin for the company
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'company_id' => $company->id,
            ]);

            event(new Registered($user));

            Auth::login($user);

            DB::commit();

            try {
                Mail::to($user->email)->send(new WelcomeQuickstartMail($user, $company));
            } catch (\Throwable $mailException) {
                Log::warning('Welcome mail could not be sent', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $mailException->getMessage(),
                ]);
            }

            return redirect()->route('admin.dashboard')->with('success', "Welkom bij TaskCheck! Je proefperiode van {$trialLabel} is gestart.");
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function seedDefaultTemplatesForCompany(Company $company): void
    {
        if ($this->seedFromSuperAdminGlobalTemplates($company)) {
            return;
        }

        $templatesByType = [
            'cleaning' => [
                [
                    'name' => 'Dagelijkse schoonmaak ronde',
                    'description' => 'Dagelijkse controle en schoonmaak van entree, sanitair en algemene ruimtes.',
                    'tasks' => [
                        [
                            'title' => 'Entree en receptie schoonmaken',
                            'description' => 'Maak alle zichtbare contactpunten en vloeren schoon.',
                            'instructions' => 'Werk van schoon naar vuil en gebruik juiste middelen per oppervlak.',
                            'proof' => 'photo',
                            'checklist_items' => ['Balie afgenomen', 'Deurkrukken/desinfectiepunten gereinigd', 'Vloer geveegd/gedweild', 'Afval verwijderd'],
                        ],
                        [
                            'title' => 'Sanitair reinigen en aanvullen',
                            'description' => 'Volledige sanitaircontrole en hygiene-aanvulling.',
                            'instructions' => 'Controleer toiletten, wastafels en verbruiksartikelen.',
                            'proof' => 'photo',
                            'checklist_items' => ['Toiletten gereinigd', 'Wastafels en kranen gereinigd', 'Zeep/tissues bijgevuld', 'Vloer droog en schoon opgeleverd'],
                        ],
                        [
                            'title' => 'Algemene ruimtes nalopen',
                            'description' => 'Controle op netheid van looproutes en werkruimtes.',
                            'instructions' => 'Verwijder zichtbaar vuil en meld bijzonderheden.',
                            'proof' => 'any',
                            'checklist_items' => ['Stofvrij gemaakt', 'Prullenbakken geleegd', 'Geen obstakels op looproutes', 'Melding gemaakt bij schade'],
                        ],
                    ],
                ],
                [
                    'name' => 'Periodieke dieptereiniging',
                    'description' => 'Wekelijkse/maandelijkse dieptereiniging van kritieke delen.',
                    'tasks' => [
                        [
                            'title' => 'Dieptereiniging keuken/pantry',
                            'description' => 'Reinig apparatuur en moeilijk bereikbare delen.',
                            'instructions' => 'Ontkalk waar nodig en noteer afwijkingen.',
                            'proof' => 'photo',
                            'checklist_items' => ['Koelkast rubbers gereinigd', 'Magnetron/oven intern gereinigd', 'Achter/onder apparaten gereinigd', 'Resultaat gefotografeerd'],
                        ],
                        [
                            'title' => 'Ramen en glaspartijen',
                            'description' => 'Streeploos reinigen van ramen en deuren.',
                            'instructions' => 'Werk in banen en controleer op strepen bij daglicht.',
                            'proof' => 'photo',
                            'checklist_items' => ['Binnenzijde ramen gereinigd', 'Glazen deuren gereinigd', 'Vingerafdrukken verwijderd'],
                        ],
                        [
                            'title' => 'Contactpunten desinfecteren',
                            'description' => 'Desinfecteer intensief gebruikte oppervlakken.',
                            'instructions' => 'Gebruik goedgekeurd desinfectiemiddel met inwerktijd.',
                            'proof' => 'text',
                            'checklist_items' => ['Deurklinken gedaan', 'Lichtschakelaars gedaan', 'Trapleuningen gedaan'],
                        ],
                    ],
                ],
                [
                    'name' => 'Oplevercontrole locatie',
                    'description' => 'Eindcontrole op kwaliteit en volledige oplevering.',
                    'tasks' => [
                        [
                            'title' => 'Visuele kwaliteitscontrole per zone',
                            'description' => 'Controleer op restvuil, strepen en vergeten taken.',
                            'instructions' => 'Loop volgens vaste route alle zones na.',
                            'proof' => 'photo',
                            'checklist_items' => ['Zone 1 akkoord', 'Zone 2 akkoord', 'Zone 3 akkoord', 'Geen restmaterialen aanwezig'],
                        ],
                        [
                            'title' => 'Afwijkingen registreren',
                            'description' => 'Leg afwijkingen vast met oorzaak en actie.',
                            'instructions' => 'Vul kort in wat is gevonden en wat is gedaan.',
                            'proof' => 'text',
                            'checklist_items' => ['Afwijking omschreven', 'Corrigerende actie ingevuld', 'Status doorgegeven'],
                        ],
                        [
                            'title' => 'Eindoplevering bevestigen',
                            'description' => 'Bevestig dat locatie opleverklaar is.',
                            'instructions' => 'Upload indien nodig documentatie van klantafspraken.',
                            'proof' => 'file',
                            'checklist_items' => ['Eindfoto toegevoegd', 'Rapport/document geupload', 'Oplevering bevestigd'],
                        ],
                    ],
                ],
            ],
            'horeca' => [
                [
                    'name' => 'Dagelijkse openingscheck horeca',
                    'description' => 'Controle van hygiene, voorraad en apparatuur voor opening.',
                    'tasks' => [
                        [
                            'title' => 'Koelingen en vriezers controleren',
                            'description' => 'Meet en registreer temperaturen voordat service start.',
                            'instructions' => 'Gebruik gekalibreerde thermometer en noteer afwijkingen.',
                            'proof' => 'photo',
                            'checklist_items' => ['Koeling temperatuur gemeten', 'Vriezer temperatuur gemeten', 'Afwijkingen gemeld'],
                        ],
                        [
                            'title' => 'Keukenwerkplekken hygieneklaar maken',
                            'description' => 'Reinig en desinfecteer alle prep-oppervlakken.',
                            'instructions' => 'Gebruik aparte doeken voor rauw/ready-to-eat zones.',
                            'proof' => 'photo',
                            'checklist_items' => ['Werkbladen gereinigd', 'Snijplanken gescheiden per productgroep', 'Handwaspunten gecontroleerd'],
                        ],
                        [
                            'title' => 'Mise-en-place basiscontrole',
                            'description' => 'Controleer voorraad en houdbaarheid van kritieke producten.',
                            'instructions' => 'Controleer THT/TGT en etiketten op datum en tijd.',
                            'proof' => 'text',
                            'checklist_items' => ['Kritieke producten op voorraad', 'Geen verlopen producten gevonden', 'Etikettering in orde'],
                        ],
                    ],
                ],
                [
                    'name' => 'HACCP controlelijst',
                    'description' => 'Dagelijkse HACCP-routine voor voedselveiligheid en traceerbaarheid.',
                    'tasks' => [
                        [
                            'title' => 'Goederenontvangst HACCP check',
                            'description' => 'Controle op temperatuur, verpakking en leverconditie.',
                            'instructions' => 'Accepteer alleen leveringen die aan HACCP-eisen voldoen.',
                            'proof' => 'photo',
                            'checklist_items' => ['Producttemperatuur geregistreerd', 'Verpakking onbeschadigd', 'Leverancier/lot vastgelegd'],
                        ],
                        [
                            'title' => 'Bewaarcondities en etikettering',
                            'description' => 'Controle op FIFO, labels en allergeneninformatie.',
                            'instructions' => 'Pas FIFO toe en label opnieuw indien nodig.',
                            'proof' => 'photo',
                            'checklist_items' => ['FIFO toegepast', 'Openingsdatums zichtbaar', 'Allergenenlabels gecontroleerd'],
                        ],
                        [
                            'title' => 'Kruisbesmetting preventie',
                            'description' => 'Check op scheiding rauw/gaar en materiaalgebruik.',
                            'instructions' => 'Gebruik kleurcodering en aparte tools per productgroep.',
                            'proof' => 'text',
                            'checklist_items' => ['Rauw/gaar gescheiden', 'Kleurcodering nageleefd', 'Handschoenen/hygiene volgens protocol'],
                        ],
                        [
                            'title' => 'Schoonmaak- en desinfectielog',
                            'description' => 'Bijwerken van uitgevoerde HACCP-schoonmaak.',
                            'instructions' => 'Vul middelen, tijden en verantwoordelijke in.',
                            'proof' => 'file',
                            'checklist_items' => ['Logboek geactualiseerd', 'Middelen correct gebruikt', 'Verantwoordelijke geregistreerd'],
                        ],
                    ],
                ],
                [
                    'name' => 'Sluitronde horeca',
                    'description' => 'Einde-dag checklist voor veilige en schone afsluiting.',
                    'tasks' => [
                        [
                            'title' => 'Keuken en bar eindschoonmaak',
                            'description' => 'Volledige eindschoonmaak van productie- en uitgiftezones.',
                            'instructions' => 'Volg sluitprotocol en check alle hotspots.',
                            'proof' => 'video',
                            'checklist_items' => ['Werkstations schoon opgeleverd', 'Bar/uitgifte gereinigd', 'Vloeren gereinigd'],
                        ],
                        [
                            'title' => 'Afval en retourstromen afronden',
                            'description' => 'Verwerk afval veilig volgens interne regels.',
                            'instructions' => 'Scheid afvalstromen en sluit containers correct af.',
                            'proof' => 'photo',
                            'checklist_items' => ['Afval gescheiden', 'Containers afgesloten', 'Buitenopslag netjes'],
                        ],
                        [
                            'title' => 'Apparatuur en veiligheid afsluiten',
                            'description' => 'Controleer apparatuur, gas/water/licht en deuren.',
                            'instructions' => 'Meld direct afwijkingen in veiligheidsstatus.',
                            'proof' => 'none',
                            'checklist_items' => ['Niet-benodigde apparatuur uit', 'Gas/water gecontroleerd', 'Deuren afgesloten'],
                        ],
                    ],
                ],
            ],
            'other' => [
                [
                    'name' => 'Dagelijkse basiscontrole',
                    'description' => 'Algemene start- en netheidscontrole voor teams zonder branchespecifiek protocol.',
                    'tasks' => [
                        [
                            'title' => 'Werkplek en materialen voorbereiden',
                            'description' => 'Controleer of alles klaarstaat om veilig te starten.',
                            'instructions' => 'Loop werkplekken na en meld direct ontbrekende materialen.',
                            'proof' => 'photo',
                            'checklist_items' => ['Werkplek opgeruimd', 'Benodigde materialen aanwezig', 'Veiligheid gecontroleerd'],
                        ],
                        [
                            'title' => 'Dagstart en prioriteiten afstemmen',
                            'description' => 'Bevestig taken, planning en verantwoordelijkheden.',
                            'instructions' => 'Leg kort vast wat vandaag de focus is.',
                            'proof' => 'text',
                            'checklist_items' => ['Dagplanning bevestigd', 'Prioriteiten afgestemd', 'Verantwoordelijke toegewezen'],
                        ],
                        [
                            'title' => 'Kwaliteits- en voortgangscheck',
                            'description' => 'Voer een tussentijdse controle uit op kwaliteit en voortgang.',
                            'instructions' => 'Documenteer opvallende punten met foto of korte notitie.',
                            'proof' => 'any',
                            'checklist_items' => ['Voortgang gecontroleerd', 'Kwaliteit akkoord of afwijking gemeld', 'Actiepunten vastgelegd'],
                        ],
                    ],
                ],
                [
                    'name' => 'Einde-dag afronding',
                    'description' => 'Standaard afsluitronde voor nette overdracht en duidelijke status.',
                    'tasks' => [
                        [
                            'title' => 'Werkplek afsluiten',
                            'description' => 'Laat werkplek schoon, veilig en overdraagbaar achter.',
                            'instructions' => 'Verwijder afval en berg materialen correct op.',
                            'proof' => 'photo',
                            'checklist_items' => ['Werkplek opgeruimd', 'Materialen opgeborgen', 'Afval afgevoerd'],
                        ],
                        [
                            'title' => 'Resultaten en aandachtspunten registreren',
                            'description' => 'Leg opgeleverde resultaten en open punten vast.',
                            'instructions' => 'Noteer kort wat af is en wat opvolging nodig heeft.',
                            'proof' => 'text',
                            'checklist_items' => ['Resultaat vastgelegd', 'Open punten benoemd', 'Volgende stap bepaald'],
                        ],
                        [
                            'title' => 'Overdracht bevestigen',
                            'description' => 'Rond de dag af met een duidelijke overdracht.',
                            'instructions' => 'Voeg indien nodig een bestand of rapport toe.',
                            'proof' => 'file',
                            'checklist_items' => ['Overdracht ingevuld', 'Bijlage toegevoegd (indien nodig)', 'Afronding bevestigd'],
                        ],
                    ],
                ],
            ],
        ];

        $companyTemplates = $templatesByType[$company->company_type ?? ''] ?? [];

        foreach ($companyTemplates as $templateData) {
            $template = TaskTemplate::create([
                'name' => $templateData['name'],
                'description' => $templateData['description'],
                'is_active' => true,
                'company_id' => $company->id,
            ]);

            foreach ($templateData['tasks'] as $index => $taskData) {
                TemplateTask::create([
                    'template_id' => $template->id,
                    'title' => $taskData['title'],
                    'description' => $taskData['description'] ?? null,
                    'instructions' => $taskData['instructions'] ?? null,
                    'required_proof_type' => $taskData['proof'],
                    'is_required' => $taskData['is_required'] ?? true,
                    'checklist_items' => $taskData['checklist_items'] ?? null,
                    'sort_order' => $index,
                    'is_active' => true,
                ]);
            }
        }
    }

    private function seedFromSuperAdminGlobalTemplates(Company $company): bool
    {
        if (!Schema::hasColumn('task_templates', 'target_company_type')) {
            return false;
        }

        $globalTemplates = TaskTemplate::withoutGlobalScopes()
            ->publishedGlobal()
            ->where(function ($query) use ($company) {
                $query->whereNull('target_company_type')
                    ->orWhere('target_company_type', $company->company_type);
            })
            ->with('templateTasks')
            ->orderBy('name')
            ->get();

        if ($globalTemplates->isEmpty()) {
            return false;
        }

        foreach ($globalTemplates as $globalTemplate) {
            $companyTemplate = TaskTemplate::withoutGlobalScopes()->create([
                'name' => $globalTemplate->name,
                'description' => $globalTemplate->description,
                'is_active' => true,
                'company_id' => $company->id,
                'source_template_id' => $globalTemplate->id,
                'source_updated_at' => $globalTemplate->source_updated_at,
                'category' => $globalTemplate->category,
                'icon' => $globalTemplate->icon,
                'frequency_label' => $globalTemplate->frequency_label,
                'frequency_type' => $globalTemplate->frequency_type,
                'is_starter_pack' => (bool) $globalTemplate->is_starter_pack,
                'starter_pack_group' => $globalTemplate->starter_pack_group,
                'khn_reference' => $globalTemplate->khn_reference,
                'compliance_rules' => $globalTemplate->compliance_rules,
            ]);

            foreach ($globalTemplate->templateTasks as $task) {
                TemplateTask::create([
                    'template_id' => $companyTemplate->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'instructions' => $task->instructions,
                    'required_proof_type' => $task->required_proof_type,
                    'is_required' => (bool) $task->is_required,
                    'checklist_items' => $task->checklist_items,
                    'attachments' => $task->attachments,
                    'validation_rules' => $task->validation_rules,
                    'start_time' => $task->start_time,
                    'end_time' => $task->end_time,
                    'sort_order' => $task->sort_order,
                    'is_active' => true,
                ]);
            }
        }

        return true;
    }
}
