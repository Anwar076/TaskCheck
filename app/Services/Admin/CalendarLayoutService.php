<?php

namespace App\Services\Admin;

use Illuminate\Support\Collection;

class CalendarLayoutService
{
    public function layoutTimedColumns(Collection $entries, int $defaultDurationMinutes = 30): Collection
    {
        if ($entries->isEmpty()) {
            return $entries;
        }

        $groups = [];
        foreach ($entries->values()->all() as $item) {
            $placed = false;
            foreach ($groups as &$group) {
                foreach ($group as $existing) {
                    if ($this->rangesOverlap($item, $existing, $defaultDurationMinutes)) {
                        $group[] = $item;
                        $placed = true;
                        break 2;
                    }
                }
            }
            unset($group);

            if (! $placed) {
                $groups[] = [$item];
            }
        }

        $groups = $this->mergeOverlappingGroups($groups, $defaultDurationMinutes);
        $laidOut = collect();

        foreach ($groups as $group) {
            // Reuse a lane as soon as its previous entry ends. A chain of
            // overlapping entries does not mean they all run simultaneously.
            $columns = [];
            $assigned = [];
            foreach (collect($group)->sortBy('start_minutes')->values() as $entry) {
                $columnIndex = 0;
                while (isset($columns[$columnIndex])) {
                    $lastEntry = $columns[$columnIndex][array_key_last($columns[$columnIndex])];
                    if (! $this->rangesOverlap($entry, $lastEntry, $defaultDurationMinutes)) {
                        break;
                    }
                    $columnIndex++;
                }
                $entry['column_index'] = $columnIndex;
                $columns[$columnIndex][] = $entry;
                $assigned[] = $entry;
            }

            $count = count($columns);
            foreach ($assigned as $entry) {
                // Expand into adjacent free lanes without covering another event.
                $span = 1;
                for ($next = $entry['column_index'] + 1; $next < $count; $next++) {
                    foreach ($columns[$next] as $other) {
                        if ($this->rangesOverlap($entry, $other, $defaultDurationMinutes)) {
                            break 2;
                        }
                    }
                    $span++;
                }
                $entry['column_count'] = $count;
                $entry['width_percent'] = (100 * $span) / $count;
                $entry['left_percent'] = ($entry['column_index'] / $count) * 100;
                // Keep a visible leading strip per lane while letting cards
                // extend underneath the next one, within the same day column.
                $entry['overlap_left_percent'] = ($entry['column_index'] / ($count + 1)) * 100;
                $entry['overlap_width_percent'] = min(
                    100 - $entry['overlap_left_percent'],
                    (($span + 1) / ($count + 1)) * 100
                );
                $entry['stack_order'] = $entry['column_index'] + 10;
                $laidOut->push($entry);
            }
        }

        return $laidOut->sortBy('start_minutes')->values();
    }

    private function mergeOverlappingGroups(array $groups, int $defaultDurationMinutes): array
    {
        $merged = true;
        while ($merged) {
            $merged = false;
            for ($i = 0; $i < count($groups); $i++) {
                for ($j = $i + 1; $j < count($groups); $j++) {
                    if ($this->groupsOverlap($groups[$i], $groups[$j], $defaultDurationMinutes)) {
                        $groups[$i] = array_merge($groups[$i], $groups[$j]);
                        array_splice($groups, $j, 1);
                        $merged = true;
                        break 2;
                    }
                }
            }
        }

        return $groups;
    }

    private function groupsOverlap(array $first, array $second, int $defaultDurationMinutes): bool
    {
        foreach ($first as $a) {
            foreach ($second as $b) {
                if ($this->rangesOverlap($a, $b, $defaultDurationMinutes)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function rangesOverlap(array $a, array $b, int $defaultDurationMinutes): bool
    {
        $aStart = (int) ($a['start_minutes'] ?? 0);
        $aEnd = (int) ($a['end_minutes'] ?? ($aStart + $defaultDurationMinutes));
        $bStart = (int) ($b['start_minutes'] ?? 0);
        $bEnd = (int) ($b['end_minutes'] ?? ($bStart + $defaultDurationMinutes));

        return $aStart < $bEnd && $bStart < $aEnd;
    }
}
