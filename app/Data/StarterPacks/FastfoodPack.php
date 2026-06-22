<?php

namespace App\Data\StarterPacks;

final class FastfoodPack
{
    /**
     * @return array<string, mixed>
     */
    public static function meta(): array
    {
        return [
            'slug' => 'fastfood',
            'name' => 'Fastfood',
            'description' => 'Compliance-checklists voor fastfoodbedrijven: frituur, bakwand, koeling, allergenen en sluitronde.',
            'icon' => 'fast-food-outline',
            'color' => 'orange',
            'template_count' => 10,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function templates(): array
    {
        return [
            TemplateBuilder::checklist(
                'Frituur opstartcontrole',
                'Dagelijks bij opening',
                'daily',
                [
                    'Is de frituur schoon aan de buitenzijde?',
                    'Is de olie op het juiste niveau?',
                    'Is de olie visueel bruikbaar?',
                    'Is de temperatuur correct ingesteld?',
                    'Zijn manden schoon?',
                    'Zijn filters/afzuiging visueel gecontroleerd?',
                    'Zijn producten voor frituur correct ontdooid/opgeslagen?',
                    'Zijn allergenen/kruisbesmettingsrisico\'s bekend?',
                    'Is de vloer rondom de frituur droog en veilig?',
                    'Zijn afwijkingen vastgelegd?',
                ],
                ['source_basis' => 'NVWA / HACCP / KHN Hygiënecode'],
            ),
            TemplateBuilder::checklist(
                'Frituurolie controle',
                'Dagelijks',
                'daily',
                [
                    'Is de olie gecontroleerd op geur/kleur?',
                    'Is schuimvorming afwezig?',
                    'Is rookvorming afwezig?',
                    'Is productkwaliteit gecontroleerd?',
                    'Is de olie gefilterd volgens planning?',
                    'Is olie vervangen indien nodig?',
                    'Is de vervanging geregistreerd?',
                    'Is oude olie veilig afgevoerd?',
                    'Is de frituur na filtering schoon achtergelaten?',
                    'Zijn afwijkingen gemeld?',
                ],
                ['source_basis' => 'NVWA / HACCP / KHN Hygiënecode'],
            ),
            TemplateBuilder::checklist(
                'Baktemperatuur/producttemperatuur',
                'Dagelijks',
                'daily',
                [
                    'Is de baktemperatuur ingesteld volgens intern protocol?',
                    'Is de bereidingstijd bekend bij medewerker?',
                    'Zijn producten volledig verhit?',
                    'Wordt batchgewijs gewerkt?',
                    'Wordt oud en nieuw product niet gemengd?',
                    'Worden warmhoudtijden bewaakt?',
                    'Zijn producten na overschrijding verwijderd?',
                    'Is warmhoudapparatuur schoon?',
                    'Is productkwaliteit gecontroleerd?',
                    'Zijn afwijkingen geregistreerd?',
                ],
                ['source_basis' => 'NVWA / HACCP / KHN Hygiënecode'],
            ),
            TemplateBuilder::checklist(
                'Mise-en-place controle',
                'Dagelijks bij opening',
                'daily',
                [
                    'Zijn toppings correct gekoeld?',
                    'Zijn sauzen voorzien van datum?',
                    'Zijn broodjes/verpakkingen schoon opgeslagen?',
                    'Zijn rauwe en bereide producten gescheiden?',
                    'Zijn allergenenproducten herkenbaar?',
                    'Is FIFO toegepast?',
                    'Zijn verlopen producten verwijderd?',
                    'Zijn bakken/containers schoon?',
                    'Zijn serveermaterialen schoon?',
                    'Zijn afwijkingen opgelost?',
                ],
                ['source_basis' => 'NVWA / HACCP / KHN Hygiënecode'],
            ),
            TemplateBuilder::checklist(
                'Koeling/vriezer fastfood',
                'Dagelijks',
                'daily',
                [
                    TemplateBuilder::temperatureTask('Vul temperatuur koeling in.'),
                    TemplateBuilder::freezerTask('Vul temperatuur vriezer in.'),
                    'Zijn burgers/snacks correct opgeslagen?',
                    'Zijn zuivel/sauzen correct gekoeld?',
                    'Zijn verpakkingen intact?',
                    'Zijn producten gedateerd?',
                    'Is FIFO toegepast?',
                    'Is vriezer vrij van overmatige ijsvorming?',
                    'Zijn deuren/rubbers in orde?',
                    'Zijn afwijkingen gemeld?',
                ],
                ['source_basis' => 'NVWA / HACCP / KHN Hygiënecode'],
            ),
            TemplateBuilder::checklist(
                'Bestelbalie/front hygiëne',
                'Meerdere keren per dag',
                'daily',
                [
                    'Is de toonbank schoon?',
                    'Zijn betaalapparaat en schermen schoon?',
                    'Zijn contactpunten gereinigd?',
                    'Zijn servetten/bestek schoon opgeslagen?',
                    'Zijn drankstations schoon?',
                    'Zijn afvalbakken niet overvol?',
                    'Is de vloer schoon en droog?',
                    'Zijn tafels/zitplaatsen schoon?',
                    'Zijn toiletten gecontroleerd?',
                    'Zijn afwijkingen opgelost?',
                ],
                ['category' => 'cleaning', 'source_basis' => 'NVWA / HACCP / KHN Hygiënecode'],
            ),
            TemplateBuilder::checklist(
                'Allergenen fastfood',
                'Wekelijks + bij menuwijziging',
                'weekly',
                [
                    'Is allergeneninformatie beschikbaar?',
                    'Zijn medewerkers geïnformeerd over allergenen?',
                    'Wordt kruisbesmetting beperkt?',
                    'Zijn sausflessen/toppings correct gelabeld?',
                    'Zijn receptwijzigingen verwerkt?',
                    'Wordt bij allergievraag navraag gedaan?',
                    'Wordt schoon materiaal gebruikt bij allergieverzoek?',
                    'Zijn allergenenproducten gescheiden opgeslagen waar mogelijk?',
                    'Zijn klachten/meldingen geregistreerd?',
                    'Zijn afwijkingen vastgelegd?',
                ],
                ['category' => 'allergens', 'source_basis' => 'NVWA / HACCP / KHN Hygiënecode'],
            ),
            TemplateBuilder::checklist(
                'Reiniging bakwand/frituurzone',
                'Dagelijks',
                'daily',
                [
                    'Is de bakwand gereinigd?',
                    'Zijn frituurmanden gereinigd?',
                    'Zijn vetspatten verwijderd?',
                    'Is de vloer ontvet?',
                    'Zijn afzuigfilters visueel gecontroleerd?',
                    'Zijn handgrepen gereinigd?',
                    'Zijn afvalbakken geleegd?',
                    'Is vetafvoer correct behandeld?',
                    'Is schoonmaakmateriaal opgeborgen?',
                    'Is foto toegevoegd na afronding?',
                ],
                [
                    'photo_on_fail' => true,
                    'category' => 'cleaning',
                    'source_basis' => 'NVWA / HACCP / KHN Hygiënecode',
                ],
            ),
            TemplateBuilder::checklist(
                'Persoonlijke hygiëne fastfood',
                'Dagelijks',
                'daily',
                [
                    'Dragen medewerkers schone kleding?',
                    'Worden handen regelmatig gewassen?',
                    'Worden handschoenen correct gebruikt?',
                    'Wordt haar vastgedragen?',
                    'Worden wondjes afgedekt?',
                    'Wordt niet gewerkt bij ziekteklachten die voedselveiligheid raken?',
                    'Zijn sieraden beperkt volgens beleid?',
                    'Wordt telefoon-/geldcontact gevolgd door handhygiëne?',
                    'Zijn handenwasmiddelen aanwezig?',
                    'Zijn afwijkingen besproken?',
                ],
                ['source_basis' => 'NVWA / HACCP / KHN Hygiënecode'],
            ),
            TemplateBuilder::checklist(
                'Sluitronde fastfood',
                'Dagelijks bij sluiting',
                'daily',
                [
                    'Zijn alle producten opgeslagen of verwijderd?',
                    'Zijn koelingen/vriezers gesloten?',
                    'Is frituur veilig uitgezet?',
                    'Is olie/vet veilig achtergelaten?',
                    'Is bakwand gereinigd?',
                    'Is vloer gereinigd?',
                    'Zijn afvalbakken geleegd?',
                    'Zijn deuren/ramen gesloten?',
                    'Zijn apparaten gecontroleerd?',
                    'Zijn open acties overgedragen?',
                ],
                ['source_basis' => 'NVWA / HACCP / KHN Hygiënecode'],
            ),
        ];
    }
}
