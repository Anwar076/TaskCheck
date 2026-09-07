<?php

namespace Tests\Unit;

use App\Services\Admin\CalendarLayoutService;
use PHPUnit\Framework\TestCase;

class CalendarLayoutServiceTest extends TestCase
{
    public function test_overlapping_entries_share_columns_while_separate_entries_use_full_width(): void
    {
        $result = (new CalendarLayoutService)->layoutTimedColumns(collect([
            ['id' => 1, 'start_minutes' => 540, 'end_minutes' => 600],
            ['id' => 2, 'start_minutes' => 570, 'end_minutes' => 630],
            ['id' => 3, 'start_minutes' => 660, 'end_minutes' => 690],
        ]));

        $this->assertSame(2, $result[0]['column_count']);
        $this->assertSame(50, $result[0]['width_percent']);
        $this->assertSame(2, $result[1]['column_count']);
        $this->assertSame(1, $result[2]['column_count']);
        $this->assertSame(100, $result[2]['width_percent']);
    }

    public function test_overlap_chain_reuses_lanes_when_entries_end(): void
    {
        $result = (new CalendarLayoutService)->layoutTimedColumns(collect([
            ['id' => 3, 'start_minutes' => 600, 'end_minutes' => 660],
            ['id' => 1, 'start_minutes' => 540, 'end_minutes' => 600],
            ['id' => 4, 'start_minutes' => 630, 'end_minutes' => 690],
            ['id' => 2, 'start_minutes' => 570, 'end_minutes' => 630],
        ]));

        $this->assertSame([1, 2, 3, 4], $result->pluck('id')->all());
        $this->assertSame([0, 1, 0, 1], $result->pluck('column_index')->all());
        $this->assertSame([2, 2, 2, 2], $result->pluck('column_count')->all());
        $this->assertSame([50, 50, 50, 50], $result->pluck('width_percent')->all());
    }

    public function test_entries_expand_into_adjacent_lanes_that_are_free(): void
    {
        $result = (new CalendarLayoutService)->layoutTimedColumns(collect([
            ['id' => 1, 'start_minutes' => 540, 'end_minutes' => 720],
            ['id' => 2, 'start_minutes' => 540, 'end_minutes' => 600],
            ['id' => 3, 'start_minutes' => 540, 'end_minutes' => 600],
            ['id' => 4, 'start_minutes' => 600, 'end_minutes' => 660],
        ]))->keyBy('id');

        $this->assertSame(3, $result[4]['column_count']);
        $this->assertEqualsWithDelta(100 / 3, $result[4]['left_percent'], 0.001);
        $this->assertEqualsWithDelta(200 / 3, $result[4]['width_percent'], 0.001);
        $this->assertEqualsWithDelta(100 / 3, $result[1]['width_percent'], 0.001);
    }

    public function test_default_duration_and_touching_boundaries(): void
    {
        $result = (new CalendarLayoutService)->layoutTimedColumns(collect([
            ['id' => 1, 'start_minutes' => 540],
            ['id' => 2, 'start_minutes' => 570],
        ]));

        $this->assertSame([100, 100], $result->pluck('width_percent')->all());
        $this->assertTrue((new CalendarLayoutService)->layoutTimedColumns(collect())->isEmpty());
    }
}
