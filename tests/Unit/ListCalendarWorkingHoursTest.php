<?php

namespace Tests\Unit;

use App\Models\Organisation\Company;
use App\Services\Admin\ListCalendarService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ListCalendarWorkingHoursTest extends TestCase
{
    public function test_week_axis_uses_longest_day_and_marks_shorter_days_as_non_working(): void
    {
        $company = new Company([
            'calendar_time_mode' => Company::CALENDAR_TIME_MODE_WORKING_HOURS,
            'working_hours' => array_merge(Company::defaultWorkingHours(), [
                'sunday' => [
                    'enabled' => true,
                    'start' => '06:00',
                    'end' => '19:00',
                ],
            ]),
        ]);

        $calendar = (new ListCalendarService())->buildCompanyWeek(
            new Collection(),
            Carbon::parse('2026-06-22'),
            $company
        );

        $sunday = collect($calendar['days'])->firstWhere('key', 'sunday');

        $this->assertSame(6, $calendar['time_axis']['start_hour']);
        $this->assertSame(21, $calendar['time_axis']['end_hour']);
        $this->assertSame('19:00', $sunday['non_working_ranges'][0]['start_time']);
        $this->assertSame('21:00', $sunday['non_working_ranges'][0]['end_time']);
    }

    public function test_week_axis_can_show_full_day_while_marking_non_working_hours(): void
    {
        $company = new Company([
            'calendar_time_mode' => Company::CALENDAR_TIME_MODE_24_HOURS,
            'working_hours' => Company::defaultWorkingHours(),
        ]);

        $calendar = (new ListCalendarService())->buildCompanyWeek(
            new Collection(),
            Carbon::parse('2026-06-22'),
            $company
        );

        $monday = collect($calendar['days'])->firstWhere('key', 'monday');

        $this->assertSame(0, $calendar['time_axis']['start_hour']);
        $this->assertSame(24, $calendar['time_axis']['end_hour']);
        $this->assertSame('00:00', $monday['non_working_ranges'][0]['start_time']);
        $this->assertSame('06:00', $monday['non_working_ranges'][0]['end_time']);
        $this->assertSame('21:00', $monday['non_working_ranges'][1]['start_time']);
        $this->assertSame('24:00', $monday['non_working_ranges'][1]['end_time']);
    }
}
