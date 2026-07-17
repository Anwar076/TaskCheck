<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checklist\TaskList;
use App\Models\Submissions\Submission;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportController extends Controller
{
    public function excel(Request $request): StreamedResponse
    {
        [$list, $start, $end] = $this->selection($request);
        $submissions = $this->submissions($list, $start, $end);
        $filename = 'ruwe-data-'.str($list->title)->slug().'-'.$start->format('Ymd').'-'.$end->format('Ymd').'.xls';

        return response()->streamDownload(function () use ($submissions, $list, $start, $end) {
            echo view('admin.reports.excel-xml', compact('submissions', 'list', 'start', 'end'))->render();
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    public function pdf(Request $request)
    {
        [$list, $start, $end] = $this->selection($request);
        $submissions = $this->submissions($list, $start, $end);
        $taskRows = $submissions->flatMap->submissionTasks;
        $deviations = $taskRows->filter(fn ($task) => in_array($task->status, ['rejected', 'redo_requested'], true)
            || filled($task->rejection_reason)
            || filled($task->redo_reason));
        $finished = $submissions->whereIn('status', ['completed', 'reviewed'])->count();
        $summary = [
            'total' => $submissions->count(),
            'finished' => $finished,
            'in_progress' => $submissions->where('status', 'in_progress')->count(),
            'rejected' => $submissions->where('status', 'rejected')->count(),
            'completion_rate' => $submissions->isNotEmpty() ? round(($finished / $submissions->count()) * 100, 1) : 0,
            'tasks_completed' => $taskRows->whereIn('status', ['completed', 'approved'])->count(),
            'tasks_total' => $taskRows->count(),
            'deviations' => $deviations->count(),
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
            'deviations', 'reviewers', 'reportNumber'
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
            ->with(['user', 'submissionTasks.task', 'submissionTasks.completedBy', 'submissionTasks.reviewer'])
            ->oldest('created_at')
            ->get();
    }
}
