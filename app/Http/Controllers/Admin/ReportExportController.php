<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checklist\TaskList;
use App\Models\Submissions\Submission;
use App\Services\Exports\RawDataXlsxExporter;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportExportController extends Controller
{
    public function excel(Request $request, RawDataXlsxExporter $exporter)
    {
        [$list, $start, $end] = $this->selection($request);
        $submissions = $this->submissions($list, $start, $end);
        $filename = 'ruwe-data-'.str($list->title)->slug().'-'.$start->format('Ymd').'-'.$end->format('Ymd').'.xlsx';
        $path = $exporter->create($list, $submissions);

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function pdf(Request $request)
    {
        [$list, $start, $end] = $this->selection($request);
        $submissions = $this->submissions($list, $start, $end);
        $taskRows = $submissions->flatMap->submissionTasks;
        $deviations = $taskRows->filter(fn ($task) => in_array($task->status, ['pending', 'rejected', 'redo_requested'], true)
            || filled($task->rejection_reason)
            || filled($task->redo_reason));
        $fullyCompleted = $submissions->filter(fn ($submission) => $submission->submissionTasks->isNotEmpty()
            && $submission->submissionTasks->every(fn ($task) => in_array($task->status, ['completed', 'approved'], true)));
        $openDeviations = $deviations->filter(fn ($task) => $task->verified_at === null);
        $closedDeviations = $deviations->filter(fn ($task) => $task->verified_at !== null);
        $finished = $fullyCompleted->count();
        $expected = $this->expectedExecutions($list, $start, $end);
        $summary = [
            'total' => $submissions->count(),
            'finished' => $finished,
            'in_progress' => $submissions->where('status', 'in_progress')->count(),
            'rejected' => $submissions->where('status', 'rejected')->count(),
            'completion_rate' => $submissions->isNotEmpty() ? round(($finished / $submissions->count()) * 100, 1) : 0,
            'tasks_completed' => $taskRows->whereIn('status', ['completed', 'approved'])->count(),
            'tasks_total' => $taskRows->count(),
            'deviations' => $deviations->count(),
            'open_deviations' => $openDeviations->count(),
            'closed_deviations' => $closedDeviations->count(),
            'incomplete' => $submissions->count() - $fullyCompleted->count(),
            'expected' => $expected,
            'coverage_rate' => $expected > 0 ? round(min(100, ($finished / $expected) * 100), 1) : null,
        ];
        $company = auth()->user()->company;
        $reportNumber = sprintf(
            'TC-HACCP-%04d-%04d-%s-%s',
            $company->id,
            $list->id,
            $start->format('Ymd'),
            $end->format('Ymd')
        );
        $reviewers = $taskRows->pluck('reviewer.name')->filter()->unique()->values();
        $trend = $submissions->groupBy(fn (Submission $submission) => $submission->created_at->format('Y-m-d'))
            ->map(fn ($items, $date) => [
                'date' => Carbon::parse($date),
                'total' => $items->count(),
                'finished' => $items->whereIn('status', ['completed', 'reviewed'])->count(),
            ])->values();

        return Pdf::loadView('admin.reports.list-pdf', compact(
            'company', 'list', 'start', 'end', 'submissions', 'summary', 'trend',
            'deviations', 'openDeviations', 'closedDeviations', 'reviewers', 'reportNumber'
        ))
            ->setPaper('a4', 'portrait')
            ->download('rapport-'.str($list->title)->slug().'-'.$start->format('Ymd').'-'.$end->format('Ymd').'.pdf');
    }

    private function selection(Request $request): array
    {
        if ((auth()->user()->company?->subscription_plan ?: 'starter') === 'starter') {
            abort(403, 'Rapportages zijn beschikbaar vanaf Professional.');
        }

        $validated = $request->validate([
            'list_id' => ['required', 'integer'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);
        $companyId = auth()->user()->company_id;
        $list = TaskList::where('company_id', $companyId)
            ->with('location')
            ->findOrFail($validated['list_id']);

        return [$list, Carbon::parse($validated['start_date'])->startOfDay(), Carbon::parse($validated['end_date'])->endOfDay()];
    }

    private function submissions(TaskList $list, Carbon $start, Carbon $end)
    {
        return Submission::query()
            ->where('company_id', $list->company_id)
            ->where('list_id', $list->id)
            ->whereBetween('created_at', [$start, $end])
            ->with(['user', 'submissionTasks.task', 'submissionTasks.completedBy', 'submissionTasks.reviewer', 'submissionTasks.correctiveActionOwner', 'submissionTasks.verifier'])
            ->oldest('created_at')
            ->get();
    }

    private function expectedExecutions(TaskList $list, Carbon $start, Carbon $end): int
    {
        if ($list->schedule_type === 'once') {
            return $list->due_date && ! $list->due_date->between($start, $end, true) ? 0 : 1;
        }

        if ($list->schedule_type === 'monthly') {
            return (int) $start->copy()->startOfMonth()->diffInMonths($end->copy()->startOfMonth()) + 1;
        }

        $count = 0;
        for ($day = $start->copy()->startOfDay(); $day->lte($end); $day->addDay()) {
            if ($list->schedule_type === 'daily' || $list->isAvailableOnDay(strtolower($day->format('l')))) {
                $count++;
            }
        }

        return $count;
    }
}
