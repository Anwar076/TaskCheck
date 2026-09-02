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
}
