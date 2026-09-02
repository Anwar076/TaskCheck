<?php

namespace App\Enums;

enum SubmissionTaskStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case REDO_REQUESTED = 'redo_requested';

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /** @return list<string> */
    public static function finishedValues(): array
    {
        return [self::COMPLETED->value, self::APPROVED->value];
    }
}
