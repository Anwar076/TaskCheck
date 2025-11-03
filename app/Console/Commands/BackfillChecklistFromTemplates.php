<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaskList;

class BackfillChecklistFromTemplates extends Command
{
    protected $signature = 'checklist:backfill {--dry-run} {--list=*}';
    protected $description = 'Backfill checklist_items on Tasks from their TemplateTask definitions for lists created from templates';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $listIds = $this->option('list');

        $query = TaskList::whereNotNull('template_id')->with(['tasks', 'template.templateTasks']);
        if (!empty($listIds)) {
            $query->whereIn('id', $listIds);
        }

        $lists = $query->get();

        if ($lists->isEmpty()) {
            $this->info('No task lists found that reference a template.');
            return 0;
        }

        $totalUpdated = 0;
        $totalChecked = 0;

        foreach ($lists as $list) {
            $this->line("Processing List {$list->id} - {$list->title}");

            $templateTasksByOrder = [];
            foreach ($list->template->templateTasks as $tt) {
                $templateTasksByOrder[$tt->sort_order] = $tt;
            }

            foreach ($list->tasks as $task) {
                $totalChecked++;

                // Skip if task already has checklist items
                if (!empty($task->checklist_items) && is_array($task->checklist_items) && count($task->checklist_items) > 0) {
                    $this->line(" - Task {$task->id} ({$task->title}) already has checklist_items, skipping");
                    continue;
                }

                // Try to find matching template task by order_index
                $matched = null;
                $orderIndex = $task->order_index ?? $task->order ?? null;
                if ($orderIndex !== null && isset($templateTasksByOrder[$orderIndex])) {
                    $matched = $templateTasksByOrder[$orderIndex];
                }

                // Fallback: try to match by exact title
                if (!$matched) {
                    foreach ($list->template->templateTasks as $tt) {
                        if (trim($tt->title) === trim($task->title)) {
                            $matched = $tt;
                            break;
                        }
                    }
                }

                if ($matched && !empty($matched->checklist_items) && is_array($matched->checklist_items) && count($matched->checklist_items) > 0) {
                    $this->line(" - Will copy checklist for Task {$task->id} from TemplateTask {$matched->id}");
                    if (!$dryRun) {
                        $task->checklist_items = $matched->checklist_items;
                        $task->save();
                        $totalUpdated++;
                    }
                } else {
                    $this->line(" - No matching checklist found for Task {$task->id} ({$task->title})");
                }
            }
        }

        $this->info("Checked {$totalChecked} tasks. Updated {$totalUpdated} tasks." . ($dryRun ? ' (dry-run)' : ''));

        return 0;
    }
}
