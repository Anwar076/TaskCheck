{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">
 <Worksheet ss:Name="Ruwe data">
  <Table>
   <Row>
    @foreach(['Inzending ID','Takenlijst','Medewerker','Inzending status','Gestart','Afgerond','Taak ID','Taak','Uitvoerder','Taakstatus','Antwoord / bewijs','Opmerking medewerker','Checklistwaarden','Bestanden','Beoordeling','Opmerking manager','Afwijzingsreden','Herstel gevraagd','Herstelreden','Taak uitgevoerd','Taak beoordeeld','Handtekening medewerker','Handtekening manager','Taakhandtekening'] as $heading)
     <Cell><Data ss:Type="String">{{ $heading }}</Data></Cell>
    @endforeach
   </Row>
   @foreach($submissions as $submission)
    @forelse($submission->submissionTasks as $submissionTask)
     <Row>
      @foreach([
       $submission->id, $list->title, $submission->user?->name, $submission->status,
       optional($submission->started_at)->format('Y-m-d H:i:s'), optional($submission->completed_at)->format('Y-m-d H:i:s'),
       $submissionTask->task_id, $submissionTask->task?->title, $submissionTask->completedBy?->name,
       $submissionTask->status, $submissionTask->proof_text, $submissionTask->employee_comment,
       json_encode($submissionTask->checklist_progress, JSON_UNESCAPED_UNICODE), json_encode($submissionTask->proof_files, JSON_UNESCAPED_UNICODE),
       $submissionTask->reviewer?->name, $submissionTask->manager_comment, $submissionTask->rejection_reason,
       $submissionTask->redo_requested ? 'Ja' : 'Nee', $submissionTask->redo_reason,
       optional($submissionTask->completed_at)->format('Y-m-d H:i:s'), optional($submissionTask->reviewed_at)->format('Y-m-d H:i:s'),
       $submission->employee_signature ? 'Aanwezig' : 'Niet aanwezig', $submission->manager_signature ? 'Aanwezig' : 'Niet aanwezig',
       $submissionTask->digital_signature ? 'Aanwezig' : 'Niet aanwezig'
      ] as $value)
       <Cell><Data ss:Type="String">{{ $value }}</Data></Cell>
      @endforeach
     </Row>
    @empty
     <Row><Cell><Data ss:Type="String">{{ $submission->id }}</Data></Cell><Cell><Data ss:Type="String">{{ $list->title }}</Data></Cell><Cell><Data ss:Type="String">{{ $submission->user?->name }}</Data></Cell><Cell><Data ss:Type="String">{{ $submission->status }}</Data></Cell></Row>
    @endforelse
   @endforeach
  </Table>
 </Worksheet>
</Workbook>
