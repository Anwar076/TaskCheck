<?php

namespace App\Data\StarterPacks;

final class HotelPack
{
    /**
     * @return array<string, mixed>
     */
    public static function meta(): array
    {
        return [
            'slug' => 'hotel',
            'name' => 'Hotel',
            'description' => 'Compliance-checklists voor hotels: ontbijtbuffet, housekeeping, sanitair, linnen, allergenen en sluitronde.',
            'icon' => 'building-outline',
            'color' => 'blue',
            'template_count' => 11,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function templates(): array
    {
        return [
            TemplateBuilder::checklist(
                'Ontbijtbuffet opstartcontrole',
                'Dagelijks bij opening',
                'daily',
                [
                    'Is buffetruimte schoon?',
                    'Zijn serveermaterialen schoon?',
                    'Zijn koude producten gekoeld geplaatst?',
                    'Zijn warme producten verwarmd geplaatst?',
                    'Zijn allergeneninformatie en productnamen beschikbaar?',
                    'Zijn tangen/lepels per product aanwezig?',
                    'Zijn producten afgedekt waar nodig?',
                    'Zijn verlopen producten verwijderd?',
                    'Is handhygiëne personeel in orde?',
                    'Zijn afwijkingen vastgelegd?',
                ],
                ['source_basis' => 'NVWA / HACCP / KHN Hygiënecode'],
            ),
            TemplateBuilder::checklist(
                'Buffet temperatuurcontrole',
                'Dagelijks',
                'daily',
                [
                    TemplateBuilder::temperatureTask('Vul temperatuur koude producten in.'),
                    [
                        'title' => 'Vul temperatuur warme producten in.',
                        'required_proof_type' => 'photo',
                        'validation_rules' => [
                            'answer_type' => 'temperature',
                            'metric' => 'temperature',
                            'min' => 63.0,
                            'comparison' => 'gte',
                            'unit' => '°C',
                            'critical' => true,
                        ],
                    ],
                    'Zijn producten binnen veilige tijd aangeboden?',
                    'Zijn producten tijdig vervangen?',
                    'Wordt oud en nieuw product niet gemengd?',
                    'Zijn producten beschermd tegen gastencontact?',
                    'Zijn serveerlepels/tangen schoon?',
                    'Zijn gemorste producten direct verwijderd?',
                    'Zijn afwijkende producten weggehaald?',
                    'Zijn acties geregistreerd?',
                ],
                ['source_basis' => 'NVWA / HACCP / KHN Hygiënecode'],
            ),
            TemplateBuilder::checklist(
                'Hotelkeuken koeling/vriezer',
                'Dagelijks',
                'daily',
                [
                    TemplateBuilder::temperatureTask('Vul temperatuur koeling in.'),
                    TemplateBuilder::freezerTask('Vul temperatuur vriezer in.'),
                    'Zijn producten gedateerd?',
                    'Zijn producten afgedekt?',
                    'Is rauw/bereid gescheiden?',
                    'Is FIFO toegepast?',
                    'Zijn verlopen producten verwijderd?',
                    'Is opslag schoon?',
                    'Sluiten deuren/rubbers goed?',
                    'Zijn afwijkingen gemeld?',
                ],
                ['source_basis' => 'NVWA / HACCP / KHN Hygiënecode'],
            ),
            TemplateBuilder::checklist(
                'Housekeeping kamercontrole',
                'Dagelijks',
                'daily',
                [
                    'Is kamer schoon opgeleverd?',
                    'Is badkamer schoon?',
                    'Is toilet schoon?',
                    'Zijn beddengoed/handdoeken schoon?',
                    'Zijn prullenbakken geleegd?',
                    'Zijn contactpunten gereinigd?',
                    'Zijn minibar/koffievoorzieningen schoon indien aanwezig?',
                    'Zijn defecten gemeld?',
                    'Zijn vergeten voorwerpen gemeld?',
                    'Is kamer vrijgegeven?',
                ],
                ['category' => 'cleaning', 'source_basis' => 'Hygiënecode / Internal Procedure'],
            ),
            TemplateBuilder::checklist(
                'Sanitair algemene ruimtes',
                'Meerdere keren per dag',
                'daily',
                [
                    'Zijn toiletten schoon?',
                    'Is zeep aanwezig?',
                    'Is toiletpapier aanwezig?',
                    'Zijn handdoekjes/droger beschikbaar?',
                    'Zijn spiegels/wastafels schoon?',
                    'Zijn vloeren schoon en droog?',
                    'Zijn afvalbakken geleegd?',
                    'Ruikt de ruimte fris?',
                    'Zijn defecten gemeld?',
                    'Zijn afwijkingen opgelost?',
                ],
                ['category' => 'cleaning', 'source_basis' => 'Hygiënecode / Internal Procedure'],
            ),
            TemplateBuilder::checklist(
                'Linnencontrole',
                'Dagelijks',
                'daily',
                [
                    'Is schoon linnen gescheiden van vuil linnen?',
                    'Is vuil linnen correct verzameld?',
                    'Is schoon linnen droog en schoon opgeslagen?',
                    'Zijn beschadigde items verwijderd?',
                    'Zijn voorraadniveaus gecontroleerd?',
                    'Is linnenruimte schoon?',
                    'Zijn transportwagens schoon?',
                    'Is er geen contact met afval/chemie?',
                    'Zijn klachten geregistreerd?',
                    'Zijn afwijkingen gemeld?',
                ],
                ['category' => 'operations', 'source_basis' => 'Hygiënecode / Internal Procedure'],
            ),
            TemplateBuilder::checklist(
                'Allergenen ontbijt/hotelrestaurant',
                'Wekelijks + bij menuwijziging',
                'weekly',
                [
                    'Is allergeneninformatie beschikbaar?',
                    'Zijn producten correct gelabeld?',
                    'Zijn medewerkers geïnformeerd?',
                    'Worden allergenenproducten gescheiden gepresenteerd waar mogelijk?',
                    'Zijn aparte tangen/lepels aanwezig?',
                    'Zijn receptwijzigingen verwerkt?',
                    'Wordt bij gastvragen navraag gedaan?',
                    'Zijn glutenvrije/halal/vega opties correct aangeduid indien aangeboden?',
                    'Zijn incidenten geregistreerd?',
                    'Zijn afwijkingen vastgelegd?',
                ],
                ['category' => 'allergens', 'source_basis' => 'NVWA / HACCP / KHN Hygiënecode'],
            ),
            TemplateBuilder::checklist(
                'Schoonmaak keuken/ontbijtruimte',
                'Dagelijks',
                'daily',
                [
                    'Zijn werkbanken gereinigd?',
                    'Zijn apparaten gereinigd?',
                    'Zijn buffetoppervlakken gereinigd?',
                    'Zijn vloeren gereinigd?',
                    'Zijn afvalbakken geleegd?',
                    'Zijn vaatwasmachine/vaatruimte gecontroleerd?',
                    'Zijn schoonmaakmiddelen opgeborgen?',
                    'Zijn doeken vervangen?',
                    'Zijn contactpunten gereinigd?',
                    'Is foto toegevoegd na afronding?',
                ],
                [
                    'photo_on_fail' => true,
                    'category' => 'cleaning',
                    'source_basis' => 'NVWA / HACCP / KHN Hygiënecode',
                ],
            ),
            TemplateBuilder::checklist(
                'Afvalbeheer hotel',
                'Dagelijks',
                'daily',
                [
                    'Zijn afvalbakken in algemene ruimtes geleegd?',
                    'Is keukenafval correct verwijderd?',
                    'Is afvalruimte schoon?',
                    'Zijn containers gesloten?',
                    'Wordt afval gescheiden volgens beleid?',
                    'Is glas/papier/restafval correct verwerkt?',
                    'Zijn lekkages gereinigd?',
                    'Is ongediertepreventie geborgd?',
                    'Zijn volle containers gemeld?',
                    'Zijn afwijkingen vastgelegd?',
                ],
                ['source_basis' => 'NVWA / Hygiënecode / Internal Procedure'],
            ),
            TemplateBuilder::checklist(
                'Ongediertepreventie hotel',
                'Wekelijks',
                'weekly',
                [
                    'Zijn er geen sporen van ongedierte?',
                    'Zijn opslagruimtes schoon?',
                    'Zijn voedselbronnen afgesloten?',
                    'Staan producten niet direct op de vloer?',
                    'Zijn deuren/ramen goed sluitend?',
                    'Zijn afvalcontainers gesloten?',
                    'Zijn meldingen van gasten geregistreerd?',
                    'Zijn lokdozen/vallen gecontroleerd indien aanwezig?',
                    'Is externe bestrijder geïnformeerd indien nodig?',
                    'Zijn foto\'s toegevoegd bij sporen?',
                ],
                ['photo_on_fail' => true, 'source_basis' => 'NVWA / Hygiënecode / Internal Procedure'],
            ),
            TemplateBuilder::checklist(
                'Avond/sluitronde hotel',
                'Dagelijks bij sluiting',
                'daily',
                [
                    'Zijn algemene ruimtes gecontroleerd?',
                    'Zijn buffet/keukenruimtes afgesloten?',
                    'Zijn koelingen/vriezers gesloten?',
                    'Zijn afvalbakken gecontroleerd?',
                    'Zijn technische storingen gemeld?',
                    'Zijn nooduitgangen vrij?',
                    'Zijn deuren/ramen gecontroleerd?',
                    'Zijn gevonden voorwerpen geregistreerd?',
                    'Zijn openstaande acties overgedragen?',
                    'Is manager geïnformeerd bij afwijkingen?',
                ],
                ['source_basis' => 'NVWA / Hygiënecode / Internal Procedure'],
            ),
        ];
    }
}
