<?php

namespace App\Http\Controllers\Api\Mobile\Admin;

use App\Http\Controllers\Api\Mobile\MobileController;
use App\Models\Organisation\Location;
use App\Models\Organisation\User;
use Illuminate\Http\Request;

class WeeklyOverviewController extends MobileController
{
    public function index(Request $request)
    {
        $user = $request->user();
        $companyId = $user->company_id;

        $startDate = $request->get('start_date', now()->startOfWeek()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfWeek()->format('Y-m-d'));

        $selectedLocationId = null;
        if ($request->filled('location_id')) {
            $candidate = (int) $request->get('location_id');
            if (Location::where('company_id', $companyId)->where('id', $candidate)->exists()) {
                $selectedLocationId = $candidate;
            }
        }

        $employees = User::query()
            ->where('company_id', $companyId)
            ->where('role', 'employee')
            ->when($selectedLocationId, fn ($q) => $q->where('location_id', $selectedLocationId))
            ->with(['submissions' => function ($query) use ($startDate, $endDate, $selectedLocationId) {
                $query->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])
                    ->when($selectedLocationId, function ($submissionQuery) use ($selectedLocationId) {
                        $submissionQuery->whereHas('taskList', fn ($taskListQuery) => $taskListQuery->where('location_id', $selectedLocationId));
                    });
            }])
            ->get();

        $overview = [];
        $summary = [
            'total_submissions' => 0,
            'completed' => 0,
            'reviewed' => 0,
            'in_progress' => 0,
            'rejected' => 0,
            'completion_rate' => 0,
            'employee_count' => $employees->count(),
            'active_employee_count' => $employees->where('is_active', true)->count(),
        ];

        foreach ($employees as $employee) {
            $total = $employee->submissions->count();
            $completed = $employee->submissions->where('status', 'completed')->count();
            $reviewed = $employee->submissions->where('status', 'reviewed')->count();
            $inProgress = $employee->submissions->where('status', 'in_progress')->count();
            $rejected = $employee->submissions->where('status', 'rejected')->count();
            $rate = $total > 0 ? round((($completed + $reviewed) / $total) * 100, 1) : 0;

            $overview[] = [
                'employee_id' => $employee->id,
                'employee' => [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'role' => $employee->role,
                ],
                'total_submissions' => $total,
                'completed' => $completed,
                'reviewed' => $reviewed,
                'in_progress' => $inProgress,
                'rejected' => $rejected,
                'completion_rate' => $rate,
            ];

            $summary['total_submissions'] += $total;
            $summary['completed'] += $completed;
            $summary['reviewed'] += $reviewed;
            $summary['in_progress'] += $inProgress;
            $summary['rejected'] += $rejected;
        }

        if ($summary['total_submissions'] > 0) {
            $summary['completion_rate'] = round((($summary['completed'] + $summary['reviewed']) / $summary['total_submissions']) * 100, 1);
        }

        $locations = Location::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return $this->success([
            'overview' => $overview,
            'summary' => $summary,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'locations' => $locations,
            'selected_location_id' => $selectedLocationId,
        ]);
    }
}
