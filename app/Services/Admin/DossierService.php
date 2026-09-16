<?php

namespace App\Services\Admin;

use App\Enums\SubmissionStatus;
use App\Helpers\ProofFileHelper;
use App\Models\Checklist\Task;
use App\Models\Checklist\TaskList;
use App\Models\Organisation\Location;
use App\Models\Submissions\Submission;
use App\Models\Submissions\SubmissionTask;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DossierService
{
    public function listsForPicker(int $companyId, ?int $locationId = null): Collection
    {
        return TaskList::query()
            ->where('company_id', $companyId)
            ->where('is_template', false)
            ->when($locationId, fn ($query) => $query->where('location_id', $locationId))
            ->with(['tasks' => fn ($query) => $query->orderBy('order')->orderBy('order_index')])
            ->orderBy('title')
            ->get();
    }

    public function locations(int $companyId): Collection
    {
        return Location::query()
            ->where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function dayOverview(int $companyId, Carbon $date, ?int $locationId = null): array
    {
        $dayStart = $date->copy()->startOfDay();
        $dayEnd = $date->copy()->endOfDay();

        $lists = TaskList::query()
            ->where('company_id', $companyId)
            ->where('is_template', false)
            ->where(function ($query) {
                $query->where('is_active', true)->orWhereHas('submissions');
            })
            ->when($locationId, fn ($query) => $query->where('location_id', $locationId))
            ->with(['location', 'tasks'])
            ->orderBy('title')
            ->get();

        $submissions = Submission::query()
            ->where('company_id', $companyId)
            ->whereBetween('created_at', [$dayStart, $dayEnd])
            ->when($locationId, function ($query) use ($locationId) {
                $query->whereHas('taskList', fn ($list) => $list->where('location_id', $locationId));
            })
            ->with([
                'user',
                'taskList.tasks' => fn ($query) => $query->orderBy('order')->orderBy('order_index'),
                'submissionTasks.task',
            ])
            ->oldest('created_at')
            ->get()
            ->groupBy('list_id');

        $rows = [];
        foreach ($lists as $list) {
            $daySubmissions = $submissions->get($list->id, collect());
            $expected = $this->listIsExpectedOn($list, $date);
            if (! $expected && $daySubmissions->isEmpty()) {
                continue;
            }

            $rows[] = [
                'list' => $list,
                'expected' => $expected,
                'filled' => $daySubmissions->isNotEmpty(),
                'status' => $this->listDayStatus($daySubmissions),
                'submissions' => $daySubmissions->map(fn (Submission $submission) => $this->presentSubmission($submission, $list->tasks))->values()->all(),
            ];
        }

        usort($rows, function (array $a, array $b) {
            $order = ['missing' => 0, 'in_progress' => 1, 'filled' => 2];

            return ($order[$a['status']] <=> $order[$b['status']]) ?: strcmp($a['list']->title, $b['list']->title);
        });

        $filled = count(array_filter($rows, fn (array $row) => $row['status'] !== 'missing'));
        $missing = count(array_filter($rows, fn (array $row) => $row['status'] === 'missing'));

        return [
            'date' => $date->copy()->startOfDay(),
            'rows' => $rows,
            'filled_count' => $filled,
            'missing_count' => $missing,
            'total_count' => count($rows),
        ];
    }

    public function taskHistory(int $companyId, int $taskId, Carbon $start, Carbon $end, ?int $locationId = null): array
    {
        $task = Task::query()
            ->whereHas('taskList', function ($query) use ($companyId, $locationId) {
                $query->where('company_id', $companyId)
                    ->when($locationId, fn ($list) => $list->where('location_id', $locationId));
            })
            ->with('taskList.location')
            ->findOrFail($taskId);

        $list = $task->taskList;
        $submissionTasks = $task->submissionTasks()
            ->whereHas('submission', function ($query) use ($companyId, $start, $end) {
                $query->where('company_id', $companyId)
                    ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()]);
            })
            ->with(['submission.user', 'completedBy'])
            ->get()
            ->groupBy(fn ($submissionTask) => optional($submissionTask->submission?->created_at)->toDateString());

        $entries = [];
        for ($day = $start->copy()->startOfDay(); $day->lte($end); $day->addDay()) {
            $expected = $this->listIsExpectedOn($list, $day);
            $dayTasks = $submissionTasks->get($day->toDateString(), collect());

            if (! $expected && $dayTasks->isEmpty()) {
                continue;
            }

            if ($dayTasks->isEmpty()) {
                $entries[] = $this->emptyCompactEntry($day->copy(), $task->title, $task->required_proof_type);
                continue;
            }

            foreach ($dayTasks as $submissionTask) {
                $entries[] = $this->presentCompactEntry($day->copy(), $submissionTask, $task->title, $submissionTask->submission, $task->required_proof_type);
            }
        }

        return [
            'task' => $task,
            'list' => $list,
            'start' => $start->copy()->startOfDay(),
            'end' => $end->copy()->endOfDay(),
            'entries' => $entries,
            'filled_count' => count(array_filter($entries, fn (array $entry) => $entry['filled'])),
            'missing_count' => count(array_filter($entries, fn (array $entry) => ! $entry['filled'])),
        ];
    }

    public function listHistory(int $companyId, int $listId, Carbon $start, Carbon $end, ?int $locationId = null): array
    {
        $list = TaskList::query()
            ->where('company_id', $companyId)
            ->when($locationId, fn ($query) => $query->where('location_id', $locationId))
            ->with(['tasks' => fn ($query) => $query->orderBy('order')->orderBy('order_index'), 'location'])
            ->findOrFail($listId);

        $submissions = Submission::query()
            ->where('company_id', $companyId)
            ->where('list_id', $list->id)
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->with(['user', 'submissionTasks.task', 'submissionTasks.completedBy'])
            ->oldest('created_at')
            ->get()
            ->groupBy(fn (Submission $submission) => $submission->created_at->toDateString());

        $entries = [];
        for ($day = $start->copy()->startOfDay(); $day->lte($end); $day->addDay()) {
            $expected = $this->listIsExpectedOn($list, $day);
            $daySubmissions = $submissions->get($day->toDateString(), collect());

            if (! $expected && $daySubmissions->isEmpty()) {
                continue;
            }

            if ($daySubmissions->isEmpty()) {
                foreach ($list->tasks as $task) {
                    $entries[] = $this->emptyCompactEntry($day->copy(), $task->title, $task->required_proof_type);
                }
                if ($list->tasks->isEmpty()) {
                    $entries[] = $this->emptyCompactEntry($day->copy(), $list->title);
                }
                continue;
            }

            foreach ($daySubmissions as $submission) {
                foreach ($this->tasksForSubmission($submission, $list->tasks) as $taskRow) {
                    $entries[] = $this->compactEntryFromPresented($day->copy(), $taskRow, $submission);
                }
            }
        }

        return [
            'task' => null,
            'list' => $list,
            'start' => $start->copy()->startOfDay(),
            'end' => $end->copy()->endOfDay(),
            'entries' => $entries,
            'filled_count' => count(array_filter($entries, fn (array $entry) => $entry['filled'])),
            'missing_count' => count(array_filter($entries, fn (array $entry) => ! $entry['filled'])),
        ];
    }

    public function listIsExpectedOn(TaskList $list, Carbon $date): bool
    {
        if ($list->weekday && strtolower((string) $list->weekday) !== strtolower($date->format('l'))) {
            return false;
        }

        $day = strtolower($date->format('l'));

        return match ($list->schedule_type) {
            'daily' => true,
            'weekly', 'custom' => $list->isAvailableOnDay($day),
            'monthly' => $date->day === 1,
            'once' => $list->due_date !== null && $list->due_date->isSameDay($date),
            default => false,
        };
    }

    private function listDayStatus(Collection $daySubmissions): string
    {
        if ($daySubmissions->isEmpty()) {
            return 'missing';
        }

        if ($daySubmissions->contains(fn (Submission $submission) => $submission->status === SubmissionStatus::IN_PROGRESS->value)) {
            return 'in_progress';
        }

        return 'filled';
    }

    private function presentSubmission(Submission $submission, ?Collection $listTasks = null): array
    {
        return [
            'id' => $submission->id,
            'status' => $submission->status,
            'employee' => $submission->user?->name,
            'submitted_at' => $submission->completed_at ?? $submission->created_at,
            'notes' => $submission->notes,
            'tasks' => $this->tasksForSubmission($submission, $listTasks),
        ];
    }

    /**
     * Every list task belongs in the dossier, including tasks that do not require a photo.
     *
     * @return list<array<string, mixed>>
     */
    private function tasksForSubmission(Submission $submission, ?Collection $listTasks = null): array
    {
        $listTasks ??= $submission->taskList?->tasks ?? collect();
        $byTaskId = $submission->submissionTasks->keyBy('task_id');
        $rows = [];

        foreach ($listTasks as $task) {
            $rows[] = $this->presentTaskRow($task, $byTaskId->get($task->id), $submission);
        }

        $listedIds = $listTasks->pluck('id')->all();
        foreach ($submission->submissionTasks as $submissionTask) {
            if (in_array($submissionTask->task_id, $listedIds, true)) {
                continue;
            }
            $rows[] = $this->presentTaskRow($submissionTask->task, $submissionTask, $submission);
        }

        return $rows;
    }

    private function presentTaskRow(?Task $task, ?SubmissionTask $submissionTask, Submission $submission): array
    {
        $proofType = $task?->required_proof_type ?? $submissionTask?->task?->required_proof_type ?? 'none';
        $title = $task?->title ?? $submissionTask?->task?->title ?? 'Taak';

        if (! $submissionTask) {
            $impliedStatus = in_array($submission->status, SubmissionStatus::finishedValues(), true) ? 'completed' : 'pending';
            $approval = $this->approval($impliedStatus, $submission->status);

            return [
                'title' => $title,
                'status' => $impliedStatus,
                'approval_key' => $approval['key'],
                'approval_label' => $approval['label'],
                'result' => null,
                'comment' => null,
                'files' => [],
                'image_path' => null,
                'proof_type' => $proofType,
                'employee' => $submission->user?->name,
                'submission_id' => $submission->id,
                'completed_at' => $submission->completed_at,
            ];
        }

        $approval = $this->approval($submissionTask->status, $submission->status);
        $files = ProofFileHelper::withAbsoluteUrls($submissionTask->proof_files);

        return [
            'title' => $title,
            'status' => $submissionTask->status,
            'approval_key' => $approval['key'],
            'approval_label' => $approval['label'],
            'result' => $submissionTask->proof_text,
            'comment' => $submissionTask->employee_comment,
            'files' => $files,
            'image_path' => $this->firstImagePath($submissionTask->proof_files),
            'proof_type' => $proofType,
            'employee' => $submissionTask->completedBy?->name ?? $submission->user?->name,
            'submission_id' => $submissionTask->submission_id,
            'completed_at' => $submissionTask->completed_at,
        ];
    }

    private function presentCompactEntry(Carbon $date, SubmissionTask $submissionTask, string $title, ?Submission $submission = null, ?string $proofType = null): array
    {
        $submission ??= $submissionTask->submission;
        if (! $submission) {
            return $this->emptyCompactEntry($date, $title, $proofType);
        }
        $row = $this->presentTaskRow($submissionTask->task, $submissionTask, $submission);

        return $this->compactEntryFromPresented($date, array_merge($row, [
            'title' => $title,
            'proof_type' => $proofType ?? $row['proof_type'],
        ]), $submission);
    }

    private function compactEntryFromPresented(Carbon $date, array $taskRow, Submission $submission): array
    {
        return [
            'date' => $date,
            'filled' => true,
            'task_title' => $taskRow['title'],
            'status' => $taskRow['status'],
            'approval_key' => $taskRow['approval_key'],
            'approval_label' => $taskRow['approval_label'],
            'employee' => $taskRow['employee'] ?? $submission->user?->name,
            'result' => $taskRow['result'],
            'comment' => $taskRow['comment'],
            'files' => $taskRow['files'],
            'image_path' => $taskRow['image_path'],
            'proof_type' => $taskRow['proof_type'] ?? 'none',
            'submission_id' => $taskRow['submission_id'] ?? $submission->id,
            'completed_at' => $taskRow['completed_at'] ?? $submission->completed_at,
        ];
    }

    private function emptyCompactEntry(Carbon $date, string $title, ?string $proofType = null): array
    {
        return [
            'date' => $date,
            'filled' => false,
            'task_title' => $title,
            'status' => 'missing',
            'approval_key' => 'missing',
            'approval_label' => 'Niet ingevuld',
            'employee' => null,
            'result' => null,
            'comment' => null,
            'files' => [],
            'image_path' => null,
            'proof_type' => $proofType ?? 'none',
            'submission_id' => null,
            'completed_at' => null,
        ];
    }

    /**
     * @return array{key: string, label: string}
     */
    private function approval(?string $taskStatus, ?string $submissionStatus): array
    {
        if (in_array($taskStatus, ['rejected', 'redo_requested'], true) || $submissionStatus === SubmissionStatus::REJECTED->value) {
            return ['key' => 'rejected', 'label' => $taskStatus === 'redo_requested' ? 'Opnieuw uitvoeren' : 'Afgewezen'];
        }
        if ($taskStatus === 'approved' || ($taskStatus === 'completed' && $submissionStatus === SubmissionStatus::REVIEWED->value)) {
            return ['key' => 'approved', 'label' => 'Goedgekeurd'];
        }
        if ($taskStatus === 'completed') {
            return ['key' => 'pending', 'label' => 'Ingediend'];
        }

        return ['key' => 'pending', 'label' => 'Nog niet beoordeeld'];
    }

    private function firstImagePath(?array $files): ?string
    {
        foreach ($files ?? [] as $file) {
            $path = is_array($file) ? ($file['path'] ?? null) : $file;
            if (! is_string($path) || $path === '') {
                continue;
            }
            $mime = is_array($file) ? ($file['mime_type'] ?? '') : '';
            $full = public_path('storage/'.$path);
            if (file_exists($full) && (str_starts_with((string) $mime, 'image/') || preg_match('/\.(jpe?g|png|webp)$/i', $path))) {
                return $full;
            }
        }

        return null;
    }

    public function withPdfThumbs(array $entries): array
    {
        foreach ($entries as $i => $entry) {
            $entries[$i]['image_path'] = $this->pdfThumb($entry['image_path'] ?? null);
        }

        return $entries;
    }

    private function pdfThumb(?string $path): ?string
    {
        if (! is_string($path) || $path === '' || ! is_file($path)) {
            return null;
        }

        $dir = storage_path('app/pdf-thumbs');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $out = $dir.'/'.md5($path.(string) filemtime($path)).'.jpg';
        if (is_file($out)) {
            return $out;
        }

        $image = $this->loadImage($path);
        if ($image === false) {
            return filesize($path) < 200000 ? $path : null;
        }

        $maxW = 180;
        $maxH = 140;
        $width = imagesx($image);
        $height = imagesy($image);
        $scale = min($maxW / max(1, $width), $maxH / max(1, $height), 1);
        $newW = max(1, (int) round($width * $scale));
        $newH = max(1, (int) round($height * $scale));
        $thumb = imagecreatetruecolor($newW, $newH);
        imagecopyresampled($thumb, $image, 0, 0, 0, 0, $newW, $newH, $width, $height);
        imagedestroy($image);
        imagejpeg($thumb, $out, 62);
        imagedestroy($thumb);

        return $out;
    }

    /**
     * @return \GdImage|false
     */
    private function loadImage(string $path)
    {
        $mime = @mime_content_type($path) ?: '';
        if (str_contains($mime, 'jpeg') || preg_match('/\.jpe?g$/i', $path)) {
            return @imagecreatefromjpeg($path);
        }
        if (str_contains($mime, 'png') || str_ends_with(strtolower($path), '.png')) {
            return @imagecreatefrompng($path);
        }
        if ((str_contains($mime, 'webp') || str_ends_with(strtolower($path), '.webp')) && function_exists('imagecreatefromwebp')) {
            return @imagecreatefromwebp($path);
        }

        return false;
    }
}
