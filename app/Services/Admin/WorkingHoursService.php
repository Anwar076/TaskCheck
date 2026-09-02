<?php

namespace App\Services\Admin;

use App\Models\Organisation\Company;

class WorkingHoursService
{
    public function timeAxisHours(array $axis): array
    {
        $hours = [];
        for ($hour = (int) $axis['start_hour']; $hour < (int) $axis['end_hour']; $hour++) {
            $hours[] = sprintf('%02d:00', $hour);
        }

        return $hours;
    }

    public function timeAxisForCompany(?Company $company, array $dayKeys): array
    {
        if ($company?->calendar_time_mode === Company::CALENDAR_TIME_MODE_24_HOURS) {
            return ['start_hour' => 0, 'end_hour' => 24, 'start_minutes' => 0, 'end_minutes' => 1440];
        }

        return $company?->workingHoursForDays($dayKeys) ?? $this->defaultTimeAxis();
    }

    public function hoursForDay(?Company $company, string $dayKey): array
    {
        return $company?->normalizedWorkingHours()[$dayKey]
            ?? Company::defaultWorkingHours()[$dayKey]
            ?? ['enabled' => true, 'start' => '06:00', 'end' => '21:00'];
    }

    public function nonWorkingRanges(?Company $company, string $dayKey, array $axis): array
    {
        $hours = $this->hoursForDay($company, $dayKey);
        $gridStart = (int) ($axis['start_minutes'] ?? 360);
        $gridEnd = (int) ($axis['end_minutes'] ?? 1260);
        $total = max(1, $gridEnd - $gridStart);
        if (! (bool) ($hours['enabled'] ?? true)) {
            return [$this->range($gridStart, $gridEnd, $gridStart, $total)];
        }

        $workStart = $this->minutes($hours['start'] ?? '06:00');
        $workEnd = $this->minutes($hours['end'] ?? '21:00');
        if ($workEnd <= $workStart) {
            return [];
        }

        $ranges = [];
        if ($workStart > $gridStart) {
            $ranges[] = $this->range($gridStart, min($workStart, $gridEnd), $gridStart, $total);
        }
        if ($workEnd < $gridEnd) {
            $ranges[] = $this->range(max($workEnd, $gridStart), $gridEnd, $gridStart, $total);
        }

        return array_values(array_filter($ranges, fn (array $range) => $range['height_percent'] > 0));
    }

    public function defaultTimeAxis(): array
    {
        return ['start_hour' => 6, 'end_hour' => 21, 'start_minutes' => 360, 'end_minutes' => 1260];
    }

    private function minutes(string $time): int
    {
        [$hour, $minute] = array_pad(array_map('intval', explode(':', $time)), 2, 0);

        return ($hour * 60) + $minute;
    }

    private function range(int $start, int $end, int $gridStart, int $total): array
    {
        $start = max($gridStart, $start);
        $end = max($start, $end);

        return [
            'start_minutes' => $start,
            'end_minutes' => $end,
            'start_time' => sprintf('%02d:%02d', intdiv($start, 60), $start % 60),
            'end_time' => sprintf('%02d:%02d', intdiv($end, 60), $end % 60),
            'top_percent' => max(0, (($start - $gridStart) / $total) * 100),
            'height_percent' => max(0, (($end - $start) / $total) * 100),
        ];
    }
}
