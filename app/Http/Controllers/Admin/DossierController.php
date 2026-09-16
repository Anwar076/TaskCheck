<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisation\Location;
use App\Services\Admin\DossierService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class DossierController extends Controller
{
    public function index(Request $request, DossierService $dossier): View
    {
        $this->ensureReportsAvailable();
        $context = $this->context($request, $dossier);

        return view('admin.reports.dossier', $context);
    }

    public function pdf(Request $request, DossierService $dossier): Response
    {
        $this->ensureReportsAvailable();
        set_time_limit(90);
        $company = auth()->user()->company;
        $payload = $this->pdfPayload($request, $dossier, $company);

        $binary = Pdf::loadView('admin.reports.dossier-compact-pdf', $payload)
            ->setPaper('a4', 'portrait')
            ->output();

        $filename = preg_replace('/[^A-Za-z0-9._-]+/', '-', $payload['filename']) ?: 'TaskCheck-uitdraai.pdf';

        return response($binary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    private function pdfPayload(Request $request, DossierService $dossier, $company): array
    {
        $companyId = (int) auth()->user()->company_id;
        $mode = $request->get('mode') === 'task' ? 'task' : 'day';
        $locationId = $this->locationId($request, $companyId);
        $generatedAt = now()->timezone('Europe/Amsterdam');

        if ($mode === 'day') {
            $date = Carbon::parse($request->get('date', now()->toDateString()))->startOfDay();
            $overview = $dossier->dayOverview($companyId, $date, $locationId);
            $entries = $dossier->withPdfThumbs($this->flattenDayEntries($overview['rows'], $date));
            $locationName = $this->locationName($locationId);

            return [
                'company' => $company,
                'filename' => 'TaskCheck-uitdraai-'.$date->format('Y-m-d').'.pdf',
                'printEntries' => $entries,
                'printMeta' => [
                    'kind' => 'Dagoverzicht',
                    'list' => 'Alle geplande en ingevulde lijsten',
                    'task' => 'Alle taken',
                    'period' => $date->locale('nl')->translatedFormat('d F Y'),
                    'location' => $locationName,
                    'filled' => $overview['filled_count'],
                    'missing' => $overview['missing_count'],
                    'generated' => $generatedAt->format('d-m-Y H:i'),
                ],
            ];
        }

        $start = Carbon::parse($request->get('start_date', now()->subDays(30)->toDateString()))->startOfDay();
        $end = Carbon::parse($request->get('end_date', now()->toDateString()))->endOfDay();
        if ($start->gt($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }
        $listId = (int) $request->get('list_id');
        abort_if($listId < 1, 422, 'Kies eerst een lijst.');
        $taskId = $request->filled('task_id') ? (int) $request->get('task_id') : null;
        $history = $taskId
            ? $dossier->taskHistory($companyId, $taskId, $start, $end, $locationId)
            : $dossier->listHistory($companyId, $listId, $start, $end, $locationId);
        abort_if($taskId && (int) $history['list']->id !== $listId, 404);
        $taskTitle = $history['task']->title ?? null;
        $period = $start->locale('nl')->translatedFormat('d M Y').' t/m '.$end->locale('nl')->translatedFormat('d M Y');

        return [
            'company' => $company,
            'filename' => 'TaskCheck-uitdraai-'.str($taskTitle ?: $history['list']->title)->slug().'.pdf',
            'printEntries' => $dossier->withPdfThumbs($history['entries']),
            'printMeta' => [
                'kind' => $taskTitle ? 'Taakuitdraai' : 'Lijstuitdraai',
                'list' => $history['list']->title,
                'task' => $taskTitle ?: 'Alle taken van deze lijst',
                'period' => $period,
                'location' => $history['list']->location?->name ?: $this->locationName($locationId),
                'filled' => $history['filled_count'],
                'missing' => $history['missing_count'],
                'generated' => $generatedAt->format('d-m-Y H:i'),
            ],
        ];
    }

    private function locationId(Request $request, int $companyId): ?int
    {
        if (! $request->filled('location_id')) {
            return null;
        }
        $candidate = (int) $request->get('location_id');

        return Location::where('company_id', $companyId)->where('id', $candidate)->exists() ? $candidate : null;
    }

    private function locationName(?int $locationId): string
    {
        if (! $locationId) {
            return 'Alle locaties';
        }

        return Location::query()->find($locationId)?->name ?: 'Alle locaties';
    }

    private function flattenDayEntries(array $rows, Carbon $date): array
    {
        $entries = [];
        foreach ($rows as $row) {
            if ($row['submissions'] === []) {
                $entries[] = [
                    'date' => $date,
                    'task_title' => $row['list']->title,
                    'filled' => false,
                    'approval_key' => 'missing',
                    'approval_label' => 'Niet ingevuld',
                    'employee' => null,
                    'result' => null,
                    'comment' => null,
                    'image_path' => null,
                    'proof_type' => 'none',
                    'files' => [],
                ];
                continue;
            }
            foreach ($row['submissions'] as $submission) {
                foreach ($submission['tasks'] as $taskRow) {
                    $entries[] = [
                        'date' => $submission['submitted_at'],
                        'task_title' => $taskRow['title'],
                        'filled' => true,
                        'approval_key' => $taskRow['approval_key'],
                        'approval_label' => $taskRow['approval_label'],
                        'employee' => $submission['employee'],
                        'result' => $taskRow['result'],
                        'comment' => $taskRow['comment'],
                        'image_path' => $taskRow['image_path'],
                        'proof_type' => $taskRow['proof_type'] ?? 'none',
                        'files' => $taskRow['files'],
                    ];
                }
            }
        }

        return $entries;
    }

    private function context(Request $request, DossierService $dossier): array
    {
        $companyId = (int) auth()->user()->company_id;
        $mode = $request->get('mode') === 'task' ? 'task' : 'day';
        $date = Carbon::parse($request->get('date', now()->toDateString()))->startOfDay();
        $start = Carbon::parse($request->get('start_date', now()->subDays(30)->toDateString()))->startOfDay();
        $end = Carbon::parse($request->get('end_date', now()->toDateString()))->endOfDay();
        if ($start->gt($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }
        if ($start->diffInDays($end) > 366) {
            $start = $end->copy()->subDays(366)->startOfDay();
        }

        $selectedLocationId = $this->locationId($request, $companyId);
        $locations = $dossier->locations($companyId);
        $pickerLists = $dossier->listsForPicker($companyId, $selectedLocationId);
        $dayOverview = $mode === 'day'
            ? $dossier->dayOverview($companyId, $date, $selectedLocationId)
            : ['rows' => [], 'filled_count' => 0, 'missing_count' => 0, 'total_count' => 0, 'date' => $date];

        $listId = $request->filled('list_id') ? (int) $request->get('list_id') : null;
        $taskId = $request->filled('task_id') ? (int) $request->get('task_id') : null;
        if ($listId && ! $pickerLists->contains('id', $listId)) {
            $listId = null;
            $taskId = null;
        }

        $history = null;
        if ($mode === 'task' && $listId && $taskId) {
            $history = $dossier->taskHistory($companyId, $taskId, $start, $end, $selectedLocationId);
            if ((int) $history['list']->id !== $listId) {
                abort(404);
            }
        } elseif ($mode === 'task' && $listId) {
            $history = $dossier->listHistory($companyId, $listId, $start, $end, $selectedLocationId);
        }

        $tasksByList = $pickerLists->mapWithKeys(fn ($list) => [
            $list->id => $list->tasks->map(fn ($task) => ['id' => $task->id, 'title' => $task->title])->values(),
        ]);

        return [
            'mode' => $mode,
            'date' => $date,
            'startDate' => $start->toDateString(),
            'endDate' => $end->toDateString(),
            'locations' => $locations,
            'selectedLocationId' => $selectedLocationId,
            'pickerLists' => $pickerLists,
            'dayOverview' => $dayOverview,
            'listId' => $listId,
            'taskId' => $taskId,
            'history' => $history,
            'tasksByList' => $tasksByList,
        ];
    }

    private function ensureReportsAvailable(): void
    {
        if (! auth()->user()->company?->hasPlanFeature('reports')) {
            abort(403, 'Rapportages zijn beschikbaar vanaf Professional.');
        }
    }
}
