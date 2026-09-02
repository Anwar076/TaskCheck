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
            $count = count($group);
            foreach (array_values($group) as $index => $entry) {
                $entry['column_index'] = $index;
                $entry['column_count'] = $count;
                $entry['width_percent'] = 100 / $count;
                $entry['left_percent'] = ($index / $count) * 100;
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
