<?php

namespace App\Models\Submissions;

use App\Models\Organisation\User;
use Illuminate\Database\Eloquent\Model;

class SubmissionTaskAuditEvent extends Model
{
    protected $fillable = [
        'submission_task_id', 'submission_id', 'company_id', 'actor_id',
        'event_type', 'from_status', 'to_status', 'snapshot',
    ];

    protected function casts(): array
    {
        return ['snapshot' => 'array'];
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function submissionTask()
    {
        return $this->belongsTo(SubmissionTask::class);
    }
}
