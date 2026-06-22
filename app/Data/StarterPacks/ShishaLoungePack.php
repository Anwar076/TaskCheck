<?php

namespace App\Data\StarterPacks;

final class ShishaLoungePack
{
    /**
     * @return array<string, mixed>
     */
    public static function meta(): array
    {
        return [
            'slug' => 'shisha',
            'name' => 'Shisha Lounge',
            'description' => 'Compliance-checklists voor shisha lounges: voedselveiligheid, materiaalreiniging, brandveiligheid, ventilatie en leeftijdscontrole.',
            'icon' => 'cafe-outline',
            'color' => 'purple',
            'template_count' => 8,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function templates(): array
    {
        return [
            TemplateBuilder::checklist(
                'Bar openingscontrole',
                'Dagelijks bij opening',
                'daily',
                [
                    'Is de bar schoon?',
                    'Zijn glazen/kopjes schoon opgeslagen?',
                    'Zijn dranken correct opgeslagen?',
                    'Zijn koelingen op temperatuur?',
                    'Zijn ijsblokjes hygiënisch opgeslagen?',
                    'Zijn handenwasmiddelen aanwezig?',
                    'Zijn afvalbakken leeg?',
                    'Zijn werkoppervlakken gereinigd?',
                    'Zijn schoonmaakmiddelen gescheiden van consumptiegoederen?',
                    'Zijn afwijkingen vastgelegd?',
                ],
                ['source_basis' => 'NVWA / HACCP / Hygiënecode'],
            ),
            TemplateBuilder::checklist(
                'Koeling dranken/ingrediënten',
                'Dagelijks',
                'daily',
                [
                    TemplateBuilder::temperatureTask('Vul temperatuur barkoeling in.'),
                    'Zijn geopende producten gedateerd?',
                    'Zijn zuivel/verse ingrediënten correct gekoeld?',
                    'Zijn verpakkingen intact?',
                    'Zijn verlopen producten verwijderd?',
                    'Is FIFO toegepast?',
                    'Is de koeling schoon?',
                    'Sluit de koeling goed?',
                    'Zijn producten afgedekt?',
                    'Zijn afwijkingen gemeld?',
                ],
                ['source_basis' => 'NVWA / HACCP / Hygiënecode'],
            ),
            TemplateBuilder::checklist(
                'Lounge schoonmaakronde',
                'Meerdere keren per dag',
                'daily',
                [
                    'Zijn tafels schoon?',
                    'Zijn stoelen/banken schoon?',
                    'Zijn vloeren schoon?',
                    'Zijn asresten veilig verwijderd?',
                    'Zijn contactpunten gereinigd?',
                    'Zijn afvalbakken niet overvol?',
                    'Zijn toiletten gecontroleerd?',
                    'Is de entree schoon?',
                    'Zijn geur/rookoverlastpunten gemeld?',
                    'Zijn afwijkingen opgelost?',
                ],
                ['category' => 'cleaning', 'source_basis' => 'NVWA / Hygiënecode / Internal Procedure'],
            ),
            TemplateBuilder::checklist(
                'Shisha materiaal reiniging',
                'Dagelijks',
                'daily',
                [
                    'Zijn gebruikte slangen/mondstukken volgens protocol gereinigd of vervangen?',
                    'Zijn waterreservoirs geleegd en gereinigd?',
                    'Zijn koppen gereinigd?',
                    'Zijn tangen en accessoires schoon?',
                    'Worden persoonlijke/disposable mondstukken aangeboden?',
                    'Is vuil en schoon materiaal gescheiden?',
                    'Zijn materialen droog opgeslagen?',
                    'Zijn beschadigde onderdelen verwijderd?',
                    'Is reiniging geregistreerd?',
                    'Zijn afwijkingen gemeld?',
                ],
                ['category' => 'cleaning', 'source_basis' => 'Hygiënecode / Internal Procedure'],
            ),
            TemplateBuilder::checklist(
                'Kooltjes/brandveiligheidscontrole',
                'Dagelijks',
                'daily',
                [
                    'Worden kooltjes veilig verhit?',
                    'Is er toezicht op hete kooltjes?',
                    'Zijn hittebestendige ondergronden gebruikt?',
                    'Zijn brandbare materialen weggehouden?',
                    'Is as veilig afgevoerd?',
                    'Zijn blusmiddelen bereikbaar?',
                    'Zijn nooduitgangen vrij?',
                    'Zijn medewerkers bekend met noodprocedure?',
                    'Zijn brandgevaren gemeld?',
                    'Zijn incidenten geregistreerd?',
                ],
                ['category' => 'safety', 'source_basis' => 'NVWA / Internal Procedure'],
            ),
            TemplateBuilder::checklist(
                'Ventilatiecontrole',
                'Dagelijks',
                'daily',
                [
                    'Werkt de ventilatie zichtbaar/merkbaar?',
                    'Zijn ventilatieroosters vrij?',
                    'Is er geen extreme rookophoping?',
                    'Zijn deuren/nooduitgangen vrij?',
                    'Is luchtkwaliteit door medewerker beoordeeld?',
                    'Zijn klachten van gasten geregistreerd?',
                    'Zijn filters volgens planning gecontroleerd?',
                    'Zijn technische storingen gemeld?',
                    'Is actie ondernomen bij slechte ventilatie?',
                    'Zijn afwijkingen vastgelegd?',
                ],
                ['category' => 'operations', 'source_basis' => 'NVWA / Internal Procedure'],
            ),
            TemplateBuilder::checklist(
                'Leeftijds-/toegangscontrole',
                'Dagelijks',
                'daily',
                [
                    'Is leeftijdscontrole uitgevoerd waar vereist?',
                    'Zijn medewerkers bekend met toegangsbeleid?',
                    'Zijn twijfelgevallen gecontroleerd met ID?',
                    'Zijn weigeringen netjes geregistreerd?',
                    'Is huisreglement zichtbaar?',
                    'Worden minderjarigen geweigerd volgens beleid?',
                    'Zijn incidenten gemeld?',
                    'Is beveiliging/leidinggevende geïnformeerd bij problemen?',
                    'Zijn cameragebieden/entreeprocedures gevolgd?',
                    'Zijn afwijkingen vastgelegd?',
                ],
                ['category' => 'operations', 'source_basis' => 'NVWA / Internal Procedure'],
            ),
            TemplateBuilder::checklist(
                'Sluitronde lounge',
                'Dagelijks bij sluiting',
                'daily',
                [
                    'Zijn alle shisha\'s verwijderd/gereinigd?',
                    'Zijn kooltjes/as veilig afgevoerd?',
                    'Zijn tafels en vloeren schoon?',
                    'Zijn barproducten opgeslagen?',
                    'Zijn koelingen gesloten?',
                    'Zijn toiletten gecontroleerd?',
                    'Zijn afvalbakken geleegd?',
                    'Zijn apparaten uitgeschakeld waar nodig?',
                    'Zijn deuren/ramen gesloten?',
                    'Zijn open acties overgedragen?',
                ],
                ['source_basis' => 'NVWA / Hygiënecode / Internal Procedure'],
            ),
        ];
    }
}
