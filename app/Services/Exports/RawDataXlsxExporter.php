<?php

namespace App\Services\Exports;

use App\Models\Checklist\TaskList;
use Illuminate\Support\Collection;
use RuntimeException;
use ZipArchive;

class RawDataXlsxExporter
{
    public function create(TaskList $list, Collection $submissions): string
    {
        $headers = [
            'Inzending ID', 'Takenlijst', 'Medewerker', 'Inzending status', 'Gestart', 'Afgerond',
            'Taak ID', 'Taak', 'Uitvoerder', 'Taakstatus', 'Antwoord / bewijs', 'Opmerking medewerker',
            'Checklistwaarden', 'Bestanden', 'Beoordelaar', 'Opmerking manager', 'Afwijzingsreden',
            'Herstel gevraagd', 'Herstelreden', 'Taak uitgevoerd', 'Taak beoordeeld',
            'Handtekening medewerker', 'Handtekening manager', 'Taakhandtekening',
            'Hygiënecode', 'HACCP-plan', 'Normreferentie', 'Corrigerende actie', 'Actiehouder',
            'Actiedeadline', 'Actie afgerond', 'Verificatienotitie', 'Geverifieerd door', 'Geverifieerd op',
        ];

        $rows = collect([$headers]);
        foreach ($submissions as $submission) {
            if ($submission->submissionTasks->isEmpty()) {
                $rows->push([$submission->id, $list->title, $submission->user?->name, $submission->status]);
                continue;
            }

            foreach ($submission->submissionTasks as $task) {
                $rows->push([
                    $submission->id, $list->title, $submission->user?->name, $submission->status,
                    $submission->started_at?->format('Y-m-d H:i:s'), $submission->completed_at?->format('Y-m-d H:i:s'),
                    $task->task_id, $task->task?->title, $task->completedBy?->name, $task->status,
                    $task->proof_text, $task->employee_comment,
                    $this->json($task->checklist_progress), $this->json($task->proof_files),
                    $task->reviewer?->name, $task->manager_comment, $task->rejection_reason,
                    $task->redo_requested ? 'Ja' : 'Nee', $task->redo_reason,
                    $task->completed_at?->format('Y-m-d H:i:s'), $task->reviewed_at?->format('Y-m-d H:i:s'),
                    $submission->employee_signature ? 'Aanwezig' : 'Niet aanwezig',
                    $submission->manager_signature ? 'Aanwezig' : 'Niet aanwezig',
                    $task->digital_signature ? 'Aanwezig' : 'Niet aanwezig',
                    $list->hygiene_code, $list->haccp_plan_reference, $task->task?->norm_reference,
                    $task->corrective_action, $task->correctiveActionOwner?->name,
                    $task->corrective_action_due_at?->format('Y-m-d H:i:s'),
                    $task->corrective_action_completed_at?->format('Y-m-d H:i:s'),
                    $task->verification_note, $task->verifier?->name, $task->verified_at?->format('Y-m-d H:i:s'),
                ]);
            }
        }

        $path = tempnam(sys_get_temp_dir(), 'taskcheck-xlsx-');
        if ($path === false) {
            throw new RuntimeException('Kon tijdelijk Excel-bestand niet aanmaken.');
        }

        $zip = new ZipArchive();
        if ($zip->open($path, ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Kon Excel-bestand niet openen.');
        }

        $zip->addFromString('[Content_Types].xml', $this->contentTypes());
        $zip->addFromString('_rels/.rels', $this->rootRelations());
        $zip->addFromString('xl/workbook.xml', $this->workbook());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelations());
        $zip->addFromString('xl/styles.xml', $this->styles());
        $zip->addFromString('xl/worksheets/sheet1.xml', $this->sheet($rows));
        $zip->addFromString('docProps/core.xml', $this->coreProperties());
        $zip->addFromString('docProps/app.xml', $this->appProperties());
        $zip->close();

        return $path;
    }

    private function sheet(Collection $rows): string
    {
        $lastColumn = $this->columnName($rows->first() ? count($rows->first()) : 1);
        $lastRow = max(1, $rows->count());
        $xmlRows = '';

        foreach ($rows->values() as $rowIndex => $row) {
            $number = $rowIndex + 1;
            $cells = '';
            foreach (array_values($row) as $columnIndex => $value) {
                $reference = $this->columnName($columnIndex + 1).$number;
                $style = $number === 1 ? 1 : 2;
                $cells .= '<c r="'.$reference.'" t="inlineStr" s="'.$style.'"><is><t xml:space="preserve">'.$this->escape($value).'</t></is></c>';
            }
            $xmlRows .= '<row r="'.$number.'"'.($number === 1 ? ' ht="24" customHeight="1"' : '').'>'.$cells.'</row>';
        }

        $widths = [12,28,22,18,20,20,10,34,22,18,34,30,34,28,22,30,30,16,30,20,20,22,22,20,28,28,28,34,22,20,20,34,22,20];
        $columns = '';
        foreach ($widths as $index => $width) {
            $column = $index + 1;
            $columns .= '<col min="'.$column.'" max="'.$column.'" width="'.$width.'" customWidth="1"/>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>'
            .'<cols>'.$columns.'</cols><sheetData>'.$xmlRows.'</sheetData>'
            .'<autoFilter ref="A1:'.$lastColumn.$lastRow.'"/>'
            .'</worksheet>';
    }

    private function styles(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<fonts count="2"><font><sz val="10"/><name val="Aptos"/></font><font><b/><color rgb="FFFFFFFF"/><sz val="10"/><name val="Aptos"/></font></fonts>'
            .'<fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FF2563EB"/><bgColor indexed="64"/></patternFill></fill></fills>'
            .'<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            .'<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            .'<cellXfs count="3"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/><xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1" applyAlignment="1"><alignment vertical="center"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0" applyAlignment="1"><alignment vertical="top" wrapText="1"/></xf></cellXfs>'
            .'<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles></styleSheet>';
    }

    private function contentTypes(): string { return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/><Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/><Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/></Types>'; }
    private function rootRelations(): string { return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/><Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/></Relationships>'; }
    private function workbook(): string { return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Ruwe data" sheetId="1" r:id="rId1"/></sheets></workbook>'; }
    private function workbookRelations(): string { return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>'; }
    private function coreProperties(): string { $now=now()->utc()->format('Y-m-d\TH:i:s\Z'); return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><dc:creator>TaskCheck</dc:creator><cp:lastModifiedBy>TaskCheck</cp:lastModifiedBy><dcterms:created xsi:type="dcterms:W3CDTF">'.$now.'</dcterms:created></cp:coreProperties>'; }
    private function appProperties(): string { return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties"><Application>TaskCheck</Application></Properties>'; }
    private function escape(mixed $value): string { return htmlspecialchars((string) ($value ?? ''), ENT_XML1 | ENT_QUOTES, 'UTF-8'); }
    private function json(mixed $value): string { return empty($value) ? '' : (json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: ''); }
    private function columnName(int $number): string { $name=''; while ($number>0) { $number--; $name=chr(65+($number%26)).$name; $number=intdiv($number,26); } return $name; }
}
