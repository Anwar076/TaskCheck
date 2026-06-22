<?php

namespace App\Data\StarterPacks;

final class BakkerijPack
{
    /**
     * @return array<string, mixed>
     */
    public static function meta(): array
    {
        return [
            'slug' => 'bakkerij',
            'name' => 'Bakkerij',
            'description' => 'Compliance-checklists voor bakkerijen op basis van HACCP/NVWA en de branche-hygiënecode voor brood- en banketbakkerijen.',
            'icon' => 'nutrition-outline',
            'color' => 'yellow',
            'template_count' => 9,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function templates(): array
    {
        return [
            TemplateBuilder::checklist(
                'Productieruimte openingscontrole',
                'Dagelijks bij opening',
                'daily',
                [
                    'Is de productieruimte schoon?',
                    'Zijn werkbanken schoon?',
                    'Zijn machines visueel schoon?',
                    'Zijn handenwaspunten beschikbaar?',
                    'Zijn zeep en handdroging aanwezig?',
                    'Zijn grondstoffen beschermd opgeslagen?',
                    'Zijn afvalbakken leeg of niet overvol?',
                    'Zijn er geen sporen van ongedierte?',
                    'Zijn schoonmaakmiddelen gescheiden opgeslagen?',
                    'Zijn afwijkingen vastgelegd?',
                ],
                ['source_basis' => 'NVWA / HACCP / Hygiënecode Brood- en Banketbakkerijen'],
            ),
            TemplateBuilder::checklist(
                'Ontvangst grondstoffen',
                'Bij levering',
                'per_batch',
                [
                    'Zijn verpakkingen intact?',
                    'Is THT/TGT gecontroleerd?',
                    'Zijn gekoelde grondstoffen op temperatuur gecontroleerd?',
                    'Zijn diepvriesproducten correct ontvangen?',
                    'Zijn allergene grondstoffen herkenbaar?',
                    'Zijn beschadigde producten geweigerd?',
                    'Zijn grondstoffen direct juist opgeslagen?',
                    'Is pakbon gecontroleerd?',
                    'Is leverancier geregistreerd?',
                    'Zijn afwijkingen vastgelegd?',
                ],
                ['source_basis' => 'NVWA / HACCP / Hygiënecode Brood- en Banketbakkerijen'],
            ),
            TemplateBuilder::checklist(
                'Opslag droge voorraad',
                'Dagelijks',
                'daily',
                [
                    'Zijn grondstoffen droog opgeslagen?',
                    'Zijn verpakkingen gesloten?',
                    'Zijn producten gedateerd?',
                    'Is FIFO toegepast?',
                    'Staan producten niet direct op de vloer?',
                    'Zijn allergenen herkenbaar opgeslagen?',
                    'Zijn schoonmaakmiddelen gescheiden?',
                    'Is voorraadruimte schoon?',
                    'Zijn sporen van ongedierte afwezig?',
                    'Zijn afwijkingen gemeld?',
                ],
                ['source_basis' => 'NVWA / HACCP / Hygiënecode Brood- en Banketbakkerijen'],
            ),
            TemplateBuilder::checklist(
                'Koeling/vriezer bakkerij',
                'Dagelijks',
                'daily',
                [
                    TemplateBuilder::temperatureTask('Vul temperatuur koeling in.'),
                    TemplateBuilder::freezerTask('Vul temperatuur vriezer in.'),
                    'Zijn room/zuivelproducten correct gekoeld?',
                    'Zijn vullingen en toppings gedateerd?',
                    'Zijn producten afgedekt?',
                    'Zijn rauwe en bereide producten gescheiden indien van toepassing?',
                    'Zijn verlopen producten verwijderd?',
                    'Is de koeling schoon?',
                    'Sluiten deuren/rubbers goed?',
                    'Zijn afwijkingen vastgelegd?',
                ],
                ['source_basis' => 'NVWA / HACCP / Hygiënecode Brood- en Banketbakkerijen'],
            ),
            TemplateBuilder::checklist(
                'Allergenen/kruisbesmetting bakkerij',
                'Wekelijks + bij menuwijziging',
                'weekly',
                [
                    'Zijn allergene ingrediënten herkenbaar?',
                    'Zijn recepturen actueel?',
                    'Zijn producten correct gelabeld?',
                    'Wordt kruisbesmetting met noten/gluten/melk/ei beperkt?',
                    'Wordt schoon materiaal gebruikt bij allergeenarme producten?',
                    'Zijn medewerkers geïnformeerd?',
                    'Worden receptwijzigingen verwerkt?',
                    'Worden allergenen bij verkoop correct gecommuniceerd?',
                    'Zijn incidenten geregistreerd?',
                    'Zijn afwijkingen vastgelegd?',
                ],
                ['category' => 'allergens', 'source_basis' => 'NVWA / HACCP / Hygiënecode Brood- en Banketbakkerijen'],
            ),
            TemplateBuilder::checklist(
                'Reiniging machines',
                'Dagelijks',
                'daily',
                [
                    'Is deegmenger gereinigd?',
                    'Is snijmachine gereinigd?',
                    'Is ovenruimte schoon?',
                    'Zijn bakplaten gereinigd?',
                    'Zijn spuitzakken/materialen gereinigd of vervangen?',
                    'Zijn kruimels/productresten verwijderd?',
                    'Zijn contactoppervlakken gedesinfecteerd indien protocol dit vereist?',
                    'Is schoonmaakmateriaal schoon opgeborgen?',
                    'Zijn defecten gemeld?',
                    'Is foto toegevoegd na reiniging?',
                ],
                [
                    'photo_on_fail' => true,
                    'category' => 'cleaning',
                    'source_basis' => 'NVWA / HACCP / Hygiënecode Brood- en Banketbakkerijen',
                ],
            ),
            TemplateBuilder::checklist(
                'Productcontrole/etikettering',
                'Dagelijks',
                'daily',
                [
                    'Zijn producten voorzien van juiste naam?',
                    'Is houdbaarheidsdatum correct?',
                    'Zijn allergenen vermeld waar nodig?',
                    'Zijn verpakkingen schoon en intact?',
                    'Zijn producten visueel goedgekeurd?',
                    'Zijn afwijkende producten verwijderd?',
                    'Is batch/productiedatum bekend?',
                    'Is opslag/presentatie correct?',
                    'Is retour/afkeur geregistreerd?',
                    'Zijn afwijkingen vastgelegd?',
                ],
                ['source_basis' => 'NVWA / HACCP / Hygiënecode Brood- en Banketbakkerijen'],
            ),
            TemplateBuilder::checklist(
                'Schoonmaak bakkerij dagelijks',
                'Dagelijks',
                'daily',
                [
                    'Zijn werkbanken gereinigd?',
                    'Zijn vloeren gereinigd?',
                    'Zijn machines uitwendig gereinigd?',
                    'Zijn spoelbakken schoon?',
                    'Zijn afvalbakken geleegd?',
                    'Zijn meel-/stofresten verwijderd?',
                    'Zijn handgrepen/contactpunten gereinigd?',
                    'Zijn schoonmaakdoeken vervangen?',
                    'Zijn schoonmaakmiddelen opgeborgen?',
                    'Is schoonmaak afgetekend?',
                ],
                ['category' => 'cleaning', 'source_basis' => 'NVWA / HACCP / Hygiënecode Brood- en Banketbakkerijen'],
            ),
            TemplateBuilder::checklist(
                'Sluitronde bakkerij',
                'Dagelijks bij sluiting',
                'daily',
                [
                    'Zijn producten correct opgeslagen?',
                    'Zijn grondstoffen afgesloten?',
                    'Zijn koelingen/vriezers gesloten?',
                    'Zijn ovens/apparaten veilig uitgeschakeld?',
                    'Zijn werkbanken schoon?',
                    'Is vloer schoon?',
                    'Zijn afvalbakken geleegd?',
                    'Zijn ramen/deuren gesloten?',
                    'Zijn openstaande afwijkingen overgedragen?',
                    'Is leidinggevende geïnformeerd?',
                ],
                ['source_basis' => 'NVWA / HACCP / Hygiënecode Brood- en Banketbakkerijen'],
            ),
        ];
    }
}
