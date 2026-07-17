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
        $scheduleRows = $this->scheduleRows($list, $submissions, $start, $end);
        $controlPointSummary = $list->tasks->map(function ($task) use ($taskRows) {
            $executions = $taskRows->where('task_id', $task->id);
            $rules = is_array($task->validation_rules) ? $task->validation_rules : [];

            return [
                'task' => $task,
                'executions' => $executions->count(),
                'completed' => $executions->whereIn('status', ['completed', 'approved'])->count(),
                'exceptions' => $executions->filter(fn ($row) => in_array($row->status, ['pending', 'rejected', 'redo_requested'], true))->count(),
                'latest_result' => $executions->sortByDesc('completed_at')->first()?->proof_text,
                'acceptance' => $task->acceptance_criteria ?: $this->acceptanceLabel($rules),
            ];
        });
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
            'TC-CTRL-%04d-%04d-%s-%s',
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
            'deviations', 'openDeviations', 'closedDeviations', 'reviewers', 'reportNumber',
            'scheduleRows', 'controlPointSummary'
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
            ->with(['location', 'tasks'])
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
        return $this->expectedDates($list, $start, $end)->count();
    }

    private function expectedDates(TaskList $list, Carbon $start, Carbon $end)
    {
        $effectiveEnd = $end->copy()->min(now()->endOfDay());
        if ($start->gt($effectiveEnd)) {
            return collect();
        }

        if ($list->schedule_type === 'once') {
            if ($list->due_date && ! $list->due_date->between($start, $effectiveEnd, true)) {
                return collect();
            }

            return collect([($list->due_date ?? $start)->copy()->startOfDay()]);
        }

        if ($list->schedule_type === 'monthly') {
            $dates = collect();
            for ($month = $start->copy()->startOfMonth(); $month->lte($effectiveEnd); $month->addMonth()) {
                $dates->push($month->copy());
            }
            return $dates;
        }

        $dates = collect();
        for ($day = $start->copy()->startOfDay(); $day->lte($effectiveEnd); $day->addDay()) {
            if ($list->schedule_type === 'daily' || $list->isAvailableOnDay(strtolower($day->format('l')))) {
                $dates->push($day->copy());
            }
        }

        return $dates;
    }

    private function scheduleRows(TaskList $list, $submissions, Carbon $start, Carbon $end)
    {
        return $this->expectedDates($list, $start, $end)->map(function (Carbon $date) use ($submissions) {
            $daySubmissions = $submissions->filter(fn ($submission) => $submission->created_at->isSameDay($date));
            $complete = $daySubmissions->contains(fn ($submission) => $submission->submissionTasks->isNotEmpty()
                && $submission->submissionTasks->every(fn ($task) => in_array($task->status, ['completed', 'approved'], true)));

            return [
                'date' => $date,
                'submissions' => $daySubmissions,
                'status' => $daySubmissions->isEmpty() ? 'missing' : ($complete ? 'complete' : 'incomplete'),
            ];
        });
    }

    private function acceptanceLabel(array $rules): string
    {
        if (empty($rules['metric'])) {
            return 'Volgens instructie / controleantwoord';
        }

        $unit = $rules['unit'] ?? '';
        $parts = [];
        if (array_key_exists('min', $rules)) {
            $parts[] = 'min. '.$rules['min'].' '.$unit;
        }
        if (array_key_exists('max', $rules)) {
            $parts[] = 'max. '.$rules['max'].' '.$unit;
        }

        return $parts !== [] ? implode(' en ', $parts) : ucfirst((string) $rules['metric']).' registreren';
    }
}
