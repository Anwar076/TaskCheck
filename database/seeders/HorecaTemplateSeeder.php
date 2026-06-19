<?php

namespace Database\Seeders;

use App\Models\Checklist\TaskTemplate;
use App\Models\Checklist\TemplateTask;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HorecaTemplateSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            foreach ($this->templates() as $templateData) {
                $template = TaskTemplate::withoutGlobalScopes()->updateOrCreate(
                    [
                        'company_id' => null,
                        'name' => $templateData['name'],
                        'target_company_type' => 'horeca',
                    ],
                    [
                        'description' => $templateData['description'],
                        'is_active' => true,
                        'category' => 'Horeca',
                        'icon' => $templateData['icon'],
                        'frequency_label' => $templateData['frequency_label'],
                        'frequency_type' => $templateData['frequency_type'],
                        'is_starter_pack' => true,
                        'starter_pack_group' => 'horeca',
                        'khn_reference' => $templateData['khn_reference'],
                        'compliance_rules' => $templateData['compliance_rules'] ?? null,
                    ]
                );

                $template->templateTasks()->delete();
                foreach ($templateData['tasks'] as $index => $task) {
                    TemplateTask::create([
                        'template_id' => $template->id,
                        'title' => $task['title'],
                        'description' => $task['description'] ?? null,
                        'instructions' => $task['instructions'] ?? null,
                        'required_proof_type' => $task['required_proof_type'] ?? 'none',
                        'is_required' => (bool) ($task['is_required'] ?? true),
                        'checklist_items' => $task['checklist_items'] ?? null,
                        'validation_rules' => $task['validation_rules'] ?? null,
                        'sort_order' => $index,
                        'is_active' => true,
                    ]);
                }
            }
        });
    }

    private function templates(): array
    {
        return [
            [
                'name' => 'Ingangscontrole Leveringen',
                'description' => 'Dagelijkse ontvangstcontrole conform KHN Hygiënecode: temperatuur, verpakking, THT en correcte acceptatie.',
                'icon' => 'truck-outline',
                'frequency_label' => 'Dagelijks',
                'frequency_type' => 'daily',
                'khn_reference' => 'KHN registratie ingangscontrole',
                'tasks' => [
                    [
                        'title' => 'Levering registreren',
                        'required_proof_type' => 'photo',
                        'checklist_items' => ['Leverancier ingevuld', 'Product ingevuld', 'Producttype gekozen (Vers/Diepvries/Droge Waren)', 'Temperatuur geregistreerd', 'Opmerking toegevoegd indien afwijking'],
                        'validation_rules' => ['form_fields' => ['leverancier' => 'text', 'product' => 'text', 'producttype' => ['vers', 'diepvries', 'droge_waren'], 'temperatuur' => 'number', 'opmerking' => 'textarea'], 'proof_required' => true],
                    ],
                    [
                        'title' => 'Acceptatiecontrole',
                        'required_proof_type' => 'photo',
                        'checklist_items' => ['Verpakking in orde', 'THT gecontroleerd', 'Correct ontvangen'],
                        'validation_rules' => ['form_fields' => ['verpakking_in_orde' => 'boolean', 'tht_gecontroleerd' => 'boolean', 'correct_ontvangen' => 'boolean'], 'critical' => true],
                    ],
                ],
            ],
            [
                'name' => 'Temperatuur Registratie',
                'description' => 'Dagelijkse temperatuurcontrole van koel- en vriesapparatuur volgens HACCP/KHN normen.',
                'icon' => 'thermometer-outline',
                'frequency_label' => 'Dagelijks',
                'frequency_type' => 'daily',
                'khn_reference' => 'KHN registratie temperaturen',
                'compliance_rules' => ['koeling_max' => 7, 'vriezer_max' => -18],
                'tasks' => [
                    ['title' => 'Koelcel 1', 'required_proof_type' => 'photo', 'validation_rules' => ['metric' => 'temperature', 'max' => 7, 'unit' => '°C', 'critical' => true]],
                    ['title' => 'Koelcel 2', 'required_proof_type' => 'photo', 'validation_rules' => ['metric' => 'temperature', 'max' => 7, 'unit' => '°C', 'critical' => true]],
                    ['title' => 'Vriezer 1', 'required_proof_type' => 'photo', 'validation_rules' => ['metric' => 'temperature', 'max' => -18, 'comparison' => 'lte', 'unit' => '°C', 'critical' => true]],
                    ['title' => 'Vriezer 2', 'required_proof_type' => 'photo', 'validation_rules' => ['metric' => 'temperature', 'max' => -18, 'comparison' => 'lte', 'unit' => '°C', 'critical' => true]],
                    ['title' => 'Werkbank koeling', 'required_proof_type' => 'photo', 'validation_rules' => ['metric' => 'temperature', 'max' => 7, 'unit' => '°C', 'critical' => true]],
                    ['title' => 'Saladiere', 'required_proof_type' => 'photo', 'validation_rules' => ['metric' => 'temperature', 'max' => 7, 'unit' => '°C', 'critical' => true]],
                ],
            ],
            [
                'name' => 'Schoonmaakrooster',
                'description' => 'Dagelijkse reiniging en desinfectie van kritieke horeca-zones.',
                'icon' => 'sparkles-outline',
                'frequency_label' => 'Dagelijks',
                'frequency_type' => 'daily',
                'khn_reference' => 'KHN registratie schoonmaakrooster',
                'tasks' => array_map(fn ($title) => [
                    'title' => $title,
                    'required_proof_type' => 'photo',
                    'checklist_items' => ['Afgevinkt', 'Foto bewijs toegevoegd'],
                    'validation_rules' => ['requires_check' => true, 'proof_required' => true],
                ], [
                    'Werkbanken reinigen',
                    'Werkbanken desinfecteren',
                    'Vloer reinigen',
                    'Vloer desinfecteren',
                    'Vaatwasser reinigen',
                    'Afvalbakken reinigen',
                    'Handcontactpunten reinigen',
                    'Handenwasgelegenheid reinigen',
                    'Spoelbakken reinigen',
                ]),
            ],
            [
                'name' => 'Bereiden en Serveren',
                'description' => 'Dagelijkse procescontrole voor bereiden, warmhouden en koud serveren.',
                'icon' => 'restaurant-outline',
                'frequency_label' => 'Dagelijks',
                'frequency_type' => 'daily',
                'khn_reference' => 'KHN registratie bereiden en serveren',
                'compliance_rules' => ['gegaard_min' => 75, 'warmhouden_min' => 60, 'koud_serveren_max' => 7],
                'tasks' => [
                    [
                        'title' => 'Bereidingscontrole product',
                        'required_proof_type' => 'photo',
                        'checklist_items' => ['Product geregistreerd', 'Kerntemperatuur ingevoerd', 'Warmhoudtemperatuur ingevoerd', 'Serveertemperatuur ingevoerd'],
                        'validation_rules' => ['fields' => ['product' => 'text', 'kerntemperatuur' => ['type' => 'number', 'min' => 75], 'warmhoudtemperatuur' => ['type' => 'number', 'min' => 60], 'serveertemperatuur' => ['type' => 'number', 'max' => 7]], 'mark_deviation_red' => true, 'critical' => true],
                    ],
                ],
            ],
            [
                'name' => 'Periodieke Hygiëne Controle',
                'description' => 'Wekelijkse borgingscontrole op hygiëne, orde en voedselveilig werken.',
                'icon' => 'shield-checkmark-outline',
                'frequency_label' => 'Wekelijks',
                'frequency_type' => 'weekly',
                'khn_reference' => 'KHN periodieke hygiënecontrole',
                'tasks' => array_map(fn ($title) => [
                    'title' => $title,
                    'required_proof_type' => 'photo',
                    'checklist_items' => ['Status: Goed of Afkeur', 'Opmerking toegevoegd', 'Foto toegevoegd'],
                    'validation_rules' => ['status_options' => ['goed', 'afkeur'], 'fields' => ['opmerking' => 'textarea'], 'critical' => true],
                ], [
                    'FIFO toegepast',
                    'Producten afgedekt',
                    'Producten gecodeerd',
                    'Persoonlijke hygiëne',
                    'Ongedierte controle',
                    'Werkbanken schoon',
                    'Koelingen schoon',
                    'Vriezers schoon',
                    'Sanitair schoon',
                ]),
            ],
            [
                'name' => 'Thermometer Kalibratie',
                'description' => 'Kalibratiecontrole van thermometers per kwartaal.',
                'icon' => 'options-outline',
                'frequency_label' => 'Elke 3 maanden',
                'frequency_type' => 'quarterly',
                'khn_reference' => 'KHN registratielijst testen thermometers',
                'tasks' => [
                    [
                        'title' => 'Kalibratie invullen',
                        'required_proof_type' => 'photo',
                        'checklist_items' => ['Kokend water temperatuur', 'Smeltend ijs temperatuur', 'Afwijking berekend', 'Actie beschreven'],
                        'validation_rules' => ['fields' => ['kokend_water_temperatuur' => 'number', 'smeltend_ijs_temperatuur' => 'number', 'afwijking' => 'number', 'actie' => 'textarea']],
                    ],
                ],
            ],
            [
                'name' => 'Goedgekeurde Leveranciers',
                'description' => 'Leveranciersregister met contactinformatie en goedkeuringsstatus.',
                'icon' => 'people-outline',
                'frequency_label' => 'Doorlopend',
                'frequency_type' => 'none',
                'khn_reference' => 'KHN registratielijst goedgekeurde leveranciers',
                'tasks' => [
                    [
                        'title' => 'Leverancier registreren',
                        'required_proof_type' => 'none',
                        'checklist_items' => ['Leverancier', 'Contactpersoon', 'Telefoon', 'Email', 'Goedgekeurd', 'Opmerking'],
                        'validation_rules' => ['fields' => ['leverancier' => 'text', 'contactpersoon' => 'text', 'telefoon' => 'text', 'email' => 'email', 'goedgekeurd' => 'boolean', 'opmerking' => 'textarea']],
                    ],
                ],
            ],
            [
                'name' => 'Ongekoelde Presentatie',
                'description' => 'Borging tijd/temperatuur bij ongekoelde presentatie van producten.',
                'icon' => 'timer-outline',
                'frequency_label' => 'Dagelijks',
                'frequency_type' => 'daily',
                'khn_reference' => 'KHN borgingslijst ongekoelde presentatie',
                'tasks' => [
                    [
                        'title' => 'Presentatiebatch registreren',
                        'required_proof_type' => 'photo',
                        'checklist_items' => ['Product', 'Batchnummer', 'Starttijd', 'Eindtijd', 'Temperatuur', 'Vernietigd?', 'Reden'],
                        'validation_rules' => ['fields' => ['product' => 'text', 'batchnummer' => 'text', 'starttijd' => 'time', 'eindtijd' => 'time', 'temperatuur' => 'number', 'vernietigd' => 'boolean', 'reden' => 'textarea'], 'critical' => true],
                    ],
                ],
            ],
            [
                'name' => 'Sous Vide Registratie',
                'description' => 'Productiecontrole voor sous-vide bereidingen.',
                'icon' => 'flask-outline',
                'frequency_label' => 'Per productie',
                'frequency_type' => 'per_production',
                'khn_reference' => 'KHN productieregistratielijst sous-vide',
                'tasks' => [
                    [
                        'title' => 'Sous-vide batch invoeren',
                        'required_proof_type' => 'photo',
                        'checklist_items' => ['Product', 'Dikte', 'Waterbad temperatuur', 'Kerntemperatuur', 'Verhittingstijd'],
                        'validation_rules' => ['fields' => ['product' => 'text', 'dikte' => 'number', 'waterbad_temperatuur' => 'number', 'kerntemperatuur' => 'number', 'verhittingstijd' => 'number']],
                    ],
                ],
            ],
            [
                'name' => 'Sushi Registratie',
                'description' => 'Batchregistratie van sushirijst en pH-borging.',
                'icon' => 'fish-outline',
                'frequency_label' => 'Per batch',
                'frequency_type' => 'per_batch',
                'khn_reference' => 'KHN productieregistratie bereiding sushirijst',
                'compliance_rules' => ['ph_max' => 4.6],
                'tasks' => [
                    [
                        'title' => 'Sushi batch pH meting',
                        'required_proof_type' => 'photo',
                        'checklist_items' => ['Product', 'pH meting'],
                        'validation_rules' => ['fields' => ['product' => 'text', 'ph_meting' => ['type' => 'number', 'max' => 4.6]], 'mark_deviation_red' => true, 'critical' => true],
                    ],
                ],
            ],
            [
                'name' => 'Aangezuurde Producten',
                'description' => 'Batchregistratie aangezuurde producten met pH-controle.',
                'icon' => 'beaker-outline',
                'frequency_label' => 'Per batch',
                'frequency_type' => 'per_batch',
                'khn_reference' => 'KHN productieregistratielijst aangezuurde producten',
                'compliance_rules' => ['ph_max' => 4.2],
                'tasks' => [
                    [
                        'title' => 'pH controle aangezuurd product',
                        'required_proof_type' => 'photo',
                        'checklist_items' => ['Product', 'pH meting'],
                        'validation_rules' => ['fields' => ['product' => 'text', 'ph' => ['type' => 'number', 'max' => 4.2]], 'mark_deviation_red' => true, 'critical' => true],
                    ],
                ],
            ],
            [
                'name' => 'Roken Registratie',
                'description' => 'Registratie van rookproces per productie.',
                'icon' => 'bonfire-outline',
                'frequency_label' => 'Per productie',
                'frequency_type' => 'per_production',
                'khn_reference' => 'KHN productieregistratielijst roken',
                'tasks' => [
                    [
                        'title' => 'Rookproces registreren',
                        'required_proof_type' => 'photo',
                        'checklist_items' => ['Product', 'Temperatuur', 'Tijd', 'Opmerking'],
                        'validation_rules' => ['fields' => ['product' => 'text', 'temperatuur' => 'number', 'tijd' => 'number', 'opmerking' => 'textarea']],
                    ],
                ],
            ],
        ];
    }
}

