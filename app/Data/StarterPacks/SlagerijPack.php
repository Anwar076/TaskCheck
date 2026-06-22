<?php

namespace App\Data\StarterPacks;

final class SlagerijPack
{
    /**
     * @return array<string, mixed>
     */
    public static function meta(): array
    {
        return [
            'slug' => 'slagerij',
            'name' => 'Slagerij',
            'description' => 'Compliance-checklists voor slagerijen en poeliers op basis van de Hygiënecode Slagers- en Poeliersbedrijf en NVWA-controles.',
            'icon' => 'butcher-outline',
            'color' => 'red',
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
                'Ontvangst vlees/grondstoffen',
                'Bij levering',
                'per_batch',
                [
                    'Is de leverancier/pakbon gecontroleerd?',
                    'Is de verpakking intact?',
                    'Is temperatuur bij ontvangst gecontroleerd?',
                    'Is het vlees visueel beoordeeld?',
                    'Is THT/TGT gecontroleerd?',
                    'Zijn producten direct gekoeld opgeslagen?',
                    'Zijn rauwe en bereide producten gescheiden?',
                    'Zijn afwijkende producten geweigerd?',
                    'Is traceerbaarheid/batchinformatie aanwezig?',
                    'Zijn afwijkingen geregistreerd?',
                ],
                ['source_basis' => 'NVWA / HACCP / Hygiënecode Slagers- en Poeliersbedrijf'],
            ),
            TemplateBuilder::checklist(
                'Koelcel temperatuurcontrole',
                'Dagelijks',
                'daily',
                [
                    TemplateBuilder::temperatureTask('Vul temperatuur koelcel 1 in.'),
                    TemplateBuilder::temperatureTask('Vul temperatuur koelcel 2 in.'),
                    'Zijn producten correct afgedekt?',
                    'Zijn rauw en bereid gescheiden?',
                    'Zijn producten gedateerd?',
                    'Is FIFO toegepast?',
                    'Hangt/staat vlees correct opgeslagen?',
                    'Is de koelcel schoon?',
                    'Sluit de deur goed?',
                    'Zijn afwijkingen gemeld?',
                ],
                ['source_basis' => 'NVWA / HACCP / Hygiënecode Slagers- en Poeliersbedrijf'],
            ),
            TemplateBuilder::checklist(
                'Vitrine temperatuurcontrole',
                'Dagelijks',
                'daily',
                [
                    TemplateBuilder::temperatureTask('Vul temperatuur vitrine in.'),
                    'Liggen producten netjes en beschermd?',
                    'Zijn rauwe en bereide producten gescheiden?',
                    'Zijn producten voorzien van juiste aanduiding?',
                    'Zijn producten niet over datum?',
                    'Is presentatie schoon?',
                    'Wordt oud product niet met nieuw product gemengd?',
                    'Zijn tangen/materialen schoon?',
                    'Zijn producten bij afwijking verwijderd/beoordeeld?',
                    'Zijn afwijkingen vastgelegd?',
                ],
                ['source_basis' => 'NVWA / HACCP / Hygiënecode Slagers- en Poeliersbedrijf'],
            ),
            TemplateBuilder::checklist(
                'Snijruimte hygiënecontrole',
                'Dagelijks bij opening',
                'daily',
                [
                    'Zijn werkbanken schoon?',
                    'Zijn snijplanken schoon?',
                    'Zijn messen schoon?',
                    'Zijn machines schoon vóór gebruik?',
                    'Zijn handenwaspunten beschikbaar?',
                    'Zijn rauwe en bereide stromen gescheiden?',
                    'Worden materialen gereinigd tussen productgroepen?',
                    'Is vloer schoon en veilig?',
                    'Zijn afvalbakken beschikbaar en niet overvol?',
                    'Zijn afwijkingen opgelost?',
                ],
                ['source_basis' => 'NVWA / HACCP / Hygiënecode Slagers- en Poeliersbedrijf'],
            ),
            TemplateBuilder::checklist(
                'Kruisbesmetting rauw/bereid',
                'Dagelijks',
                'daily',
                [
                    'Wordt rauw vlees gescheiden van bereide producten?',
                    'Worden aparte materialen gebruikt?',
                    'Worden handen gewassen tussen werkzaamheden?',
                    'Worden machines gereinigd tussen productgroepen?',
                    'Zijn opslagzones duidelijk gescheiden?',
                    'Zijn allergenen herkenbaar?',
                    'Wordt schoon en vuil materiaal gescheiden?',
                    'Zijn producten afgedekt?',
                    'Wordt retourproduct apart gehouden?',
                    'Zijn afwijkingen geregistreerd?',
                ],
                ['source_basis' => 'NVWA / HACCP / Hygiënecode Slagers- en Poeliersbedrijf'],
            ),
            TemplateBuilder::checklist(
                'Reiniging machines/messen',
                'Dagelijks',
                'daily',
                [
                    'Is gehaktmolen gereinigd?',
                    'Is snijmachine gereinigd?',
                    'Zijn messen gereinigd?',
                    'Zijn hakblokken/snijdelen gereinigd?',
                    'Zijn werkbanken gereinigd?',
                    'Zijn contactoppervlakken gedesinfecteerd volgens protocol?',
                    'Zijn onderdelen droog en schoon opgeslagen?',
                    'Zijn schoonmaakmiddelen correct gebruikt?',
                    'Zijn defecten of slijtage gemeld?',
                    'Is reiniging afgetekend?',
                ],
                ['category' => 'cleaning', 'source_basis' => 'NVWA / HACCP / Hygiënecode Slagers- en Poeliersbedrijf'],
            ),
            TemplateBuilder::checklist(
                'Persoonlijke hygiëne slagerij',
                'Dagelijks',
                'daily',
                [
                    'Dragen medewerkers schone bedrijfskleding?',
                    'Worden handen gewassen bij start werkzaamheden?',
                    'Worden handen gewassen na rauw vlees/afval/schoonmaak?',
                    'Zijn wondjes afgedekt?',
                    'Wordt haar/baard hygiënisch beheerd volgens beleid?',
                    'Worden handschoenen correct gebruikt?',
                    'Wordt niet gewerkt bij relevante ziekteklachten?',
                    'Zijn sieraden beperkt volgens beleid?',
                    'Zijn handenwasmiddelen aanwezig?',
                    'Zijn afwijkingen besproken?',
                ],
                ['source_basis' => 'NVWA / HACCP / Hygiënecode Slagers- en Poeliersbedrijf'],
            ),
            TemplateBuilder::checklist(
                'THT/TGT en traceerbaarheid',
                'Dagelijks',
                'daily',
                [
                    'Zijn alle producten voorzien van datum?',
                    'Is herkomst/batchinformatie beschikbaar?',
                    'Zijn zelfgemaakte producten geregistreerd?',
                    'Zijn producten met verlopen datum verwijderd?',
                    'Is FIFO toegepast?',
                    'Zijn geopende producten gedateerd?',
                    'Zijn samengestelde producten herleidbaar?',
                    'Zijn retouren/afkeur apart geregistreerd?',
                    'Zijn etiketten correct?',
                    'Zijn afwijkingen vastgelegd?',
                ],
                ['source_basis' => 'NVWA / HACCP / Hygiënecode Slagers- en Poeliersbedrijf'],
            ),
            TemplateBuilder::checklist(
                'Afval/dierlijke resten',
                'Dagelijks',
                'daily',
                [
                    'Wordt snijafval correct verzameld?',
                    'Wordt afval tijdig verwijderd?',
                    'Zijn afvalbakken afsluitbaar waar nodig?',
                    'Is afval gescheiden volgens beleid?',
                    'Is afvalruimte schoon?',
                    'Zijn containers gesloten?',
                    'Zijn lekkages direct gereinigd?',
                    'Wordt geur-/ongedierteoverlast voorkomen?',
                    'Is vet/vocht correct afgevoerd?',
                    'Zijn afwijkingen gemeld?',
                ],
                ['source_basis' => 'NVWA / HACCP / Hygiënecode Slagers- en Poeliersbedrijf'],
            ),
            TemplateBuilder::checklist(
                'Sluitronde slagerij',
                'Dagelijks bij sluiting',
                'daily',
                [
                    'Zijn alle producten correct gekoeld opgeslagen?',
                    'Zijn vitrines veilig leeg/afgedekt volgens beleid?',
                    'Zijn koelcellen gesloten?',
                    'Zijn machines gereinigd?',
                    'Zijn messen/materialen opgeborgen?',
                    'Zijn werkbanken schoon?',
                    'Zijn vloeren gereinigd?',
                    'Zijn afvalbakken geleegd?',
                    'Zijn deuren/ramen gesloten?',
                    'Zijn openstaande afwijkingen overgedragen?',
                ],
                ['source_basis' => 'NVWA / HACCP / Hygiënecode Slagers- en Poeliersbedrijf'],
            ),
        ];
    }
}
