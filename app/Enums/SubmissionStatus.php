<?php

namespace App\Enums;

enum SubmissionStatus: string
{
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case REVIEWED = 'reviewed';
    case REJECTED = 'rejected';

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /** @return list<string> */
    public static function finishedValues(): array
    {
        return [self::COMPLETED->value, self::REVIEWED->value];
    }

    /** @return list<string> */
    public static function closedValues(): array
    {
        return [self::REVIEWED->value, self::REJECTED->value];
    }
}
