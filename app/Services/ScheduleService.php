<?php

namespace App\Services;

use App\Models\TaskList;
use App\Models\ListAssignment;
use App\Models\Submission;
use Carbon\Carbon;

class ScheduleService
{
    /**
     * Get all task lists that should be available for a user on a specific date
     */
    public function getScheduledTasksForUser($user, $date = null)
    {
        $date = $date ? Carbon::parse($date) : now();
        $availableLists = collect();

        // Get all assignments for this user
        $assignments = $this->getUserAssignments($user, $date);

        foreach ($assignments as $assignment) {
            $taskList = $assignment->taskList;
            
            // Handle main lists with daily sublists
            if ($taskList->isMainList() && $taskList->schedule_type === 'daily' && $taskList->subLists->count() > 0) {
                // For main lists with daily sublists, get the appropriate sublist for today
                $today = strtolower($date->format('l')); // monday, tuesday, etc.
                
                // Check if it's weekend and we should show all sublists
                if (in_array($today, ['saturday', 'sunday'])) {
                    // On weekends, show all weekday sublists for cleaning lists (same company only)
                    $weekdaySubLists = $taskList->subLists()
                        ->whereIn('weekday', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])
                        ->where('is_active', true)
                        ->where('company_id', $user->company_id)
                        ->get();
                    
                    foreach ($weekdaySubLists as $subList) {
                        if (!$this->isTaskListCompletedOnDate($subList, $user, $date)) {
                            $availableLists->push($subList);
                        }
                    }
                } else {
                    // On weekdays, show only today's sublist (same company only)
                    $todaySubList = $taskList->subLists()
                        ->where('weekday', $today)
                        ->where('is_active', true)
                        ->where('company_id', $user->company_id)
                        ->first();
                    
                    if ($todaySubList && !$this->isTaskListCompletedOnDate($todaySubList, $user, $date)) {
                        $availableLists->push($todaySubList);
                    }
                }
            } else {
                // Regular task list or child list - check if it should be available based on its schedule
                if ($this->shouldTaskListBeAvailable($taskList, $date)) {
                    // Check if already completed today
                    if (!$this->isTaskListCompletedOnDate($taskList, $user, $date)) {
                        $availableLists->push($taskList);
                    }
                }
            }
        }

        return $availableLists->unique('id');
    }

    /**
     * Get all assignments for a user
     */
private function getUserAssignments($user, $date)
    {
        $companyId = $user->company_id;
        
        // Direct assignments - only from same company
        $directAssignments = ListAssignment::with(['taskList'])
            ->where('user_id', $user->id)
            ->where('assigned_date', '<=', $date)
            ->where('is_active', true)
            ->whereHas('taskList', function ($query) use ($companyId) {
                $query->where('is_active', true)
                      ->where('company_id', $companyId);
            });

        // Department assignments - only from same company
        $departmentAssignments = ListAssignment::with(['taskList'])
            ->where('department', $user->department)
            ->where('assigned_date', '<=', $date)
            ->where('is_active', true)
            ->whereHas('taskList', function ($query) use ($companyId) {
                $query->where('is_active', true)
                      ->where('company_id', $companyId);
            });

        // Role assignments - only from same company
        $roleAssignments = ListAssignment::with(['taskList'])
            ->where('role', $user->role)
            ->where('assigned_date', '<=', $date)
            ->where('is_active', true)
            ->whereHas('taskList', function ($query) use ($companyId) {
                $query->where('is_active', true)
                      ->where('company_id', $companyId);
            });

        return $directAssignments->get()
            ->merge($departmentAssignments->get())
            ->merge($roleAssignments->get())
            ->unique('list_id');
    }

    /**
     * Check if a task list should be available on a specific date based on its schedule
     */
    private function shouldTaskListBeAvailable($taskList, $date)
    {
        // If it's a sub-list (daily task), check weekday
        if ($taskList->isDailySubList()) {
            $dayOfWeek = strtolower($date->format('l'));
            return $taskList->weekday === $dayOfWeek;
        }

        // If it's a weekly structure list, check if it has tasks for today
        if ($taskList->hasWeeklyStructure()) {
            $dayOfWeek = strtolower($date->format('l'));
            // Check if there are tasks for today OR general tasks (no specific weekday)
            return $taskList->tasks()
                ->where('is_active', true)
                ->where(function ($query) use ($dayOfWeek) {
                    $query->where('weekday', $dayOfWeek)  // Tasks for today
                          ->orWhereNull('weekday');        // General tasks
                })
                ->exists();
        }

        // If it's a main list, check schedule type
        if ($taskList->isMainList()) {
            return $this->checkMainListSchedule($taskList, $date);
        }

        return true;
    }

    /**
     * Check if a main list should be available based on its schedule type
     */
    private function checkMainListSchedule($taskList, $date)
    {
        switch ($taskList->schedule_type) {
            case 'once':
                // One-time tasks: check if due date hasn't passed
                if ($taskList->due_date) {
                    return $date->lte($taskList->due_date);
                }
                return true;

            case 'daily':
                // Daily tasks: available every day
                // Check if it has sub-lists for specific days
                if ($taskList->subLists()->exists()) {
                    // Get today's sub-list
                    $todaysSubList = $taskList->getTodaySubList();
                    return $todaysSubList !== null;
                }
                return true;

            case 'weekly':
                // Weekly tasks: check schedule config or default to once per week
                $scheduleConfig = $taskList->schedule_config;
                if ($scheduleConfig && isset($scheduleConfig['weekdays'])) {
                    $dayOfWeek = strtolower($date->format('l'));
                    return in_array($dayOfWeek, $scheduleConfig['weekdays']);
                }
                // Default to Monday if no config
                return $date->isMonday();

            case 'monthly':
                // Monthly tasks: check schedule config or default to first day of month
                $scheduleConfig = $taskList->schedule_config;
                if ($scheduleConfig && isset($scheduleConfig['day_of_month'])) {
                    return $date->day == $scheduleConfig['day_of_month'];
                }
                // Default to first day of month
                return $date->day == 1;

            case 'custom':
                // Custom schedule: check schedule config
                return $this->checkCustomSchedule($taskList, $date);

            default:
                return true;
        }
    }

    /**
     * Check custom schedule configuration
     */
    private function checkCustomSchedule($taskList, $date)
    {
        $scheduleConfig = $taskList->schedule_config;
        
        if (!$scheduleConfig) {
            return true;
        }

        // Check various custom schedule types
        if (isset($scheduleConfig['type'])) {
            switch ($scheduleConfig['type']) {
                case 'specific_days':
                    if (isset($scheduleConfig['days'])) {
                        $dayOfWeek = strtolower($date->format('l'));
                        return in_array($dayOfWeek, $scheduleConfig['days']);
                    }
                    break;

                case 'interval':
                    if (isset($scheduleConfig['interval_days']) && isset($scheduleConfig['start_date'])) {
                        $startDate = Carbon::parse($scheduleConfig['start_date']);
                        $daysDiff = $startDate->diffInDays($date);
                        return $daysDiff % $scheduleConfig['interval_days'] === 0;
                    }
                    break;

                case 'date_range':
                    if (isset($scheduleConfig['start_date']) && isset($scheduleConfig['end_date'])) {
                        $startDate = Carbon::parse($scheduleConfig['start_date']);
                        $endDate = Carbon::parse($scheduleConfig['end_date']);
                        return $date->between($startDate, $endDate);
                    }
                    break;
            }
        }

        return true;
    }

    /**
     * Check if a task list has been completed by a user on a specific date
     */
    private function isTaskListCompletedOnDate($taskList, $user, $date)
    {
        return Submission::where('user_id', $user->id)
            ->where('list_id', $taskList->id)
            ->whereDate('created_at', $date)
            ->where('status', 'completed')
            ->exists();
    }

    /**
     * Get the next scheduled date for a task list
     */
    public function getNextScheduledDate($taskList, $afterDate = null)
    {
        $afterDate = $afterDate ? Carbon::parse($afterDate) : now();
        
        switch ($taskList->schedule_type) {
            case 'daily':
                return $afterDate->copy()->addDay();
                
            case 'weekly':
                return $afterDate->copy()->addWeek();
                
            case 'monthly':
                return $afterDate->copy()->addMonth();
                
            case 'once':
                return $taskList->due_date;
                
            case 'custom':
                // This would need specific logic based on custom config
                return $this->getNextCustomScheduledDate($taskList, $afterDate);
                
            default:
                return null;
        }
    }

    /**
     * Get next date for custom schedule
     */
    private function getNextCustomScheduledDate($taskList, $afterDate)
    {
        $scheduleConfig = $taskList->schedule_config;
        
        if (!$scheduleConfig || !isset($scheduleConfig['type'])) {
            return null;
        }

        switch ($scheduleConfig['type']) {
            case 'interval':
                if (isset($scheduleConfig['interval_days'])) {
                    return $afterDate->copy()->addDays($scheduleConfig['interval_days']);
                }
                break;
                
            case 'specific_days':
                // Find next occurrence of specified days
                if (isset($scheduleConfig['days'])) {
                    $nextDate = $afterDate->copy()->addDay();
                    while (!in_array(strtolower($nextDate->format('l')), $scheduleConfig['days'])) {
                        $nextDate->addDay();
                        // Safety check to avoid infinite loop
                        if ($nextDate->diffInDays($afterDate) > 7) {
                            break;
                        }
                    }
                    return $nextDate;
                }
                break;
        }

        return null;
    }
}