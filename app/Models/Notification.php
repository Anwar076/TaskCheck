<?php

namespace App\Models;

use App\Services\Notifications\WebPushService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::created(function (Notification $notification) {
            try {
                app(WebPushService::class)->sendForNotification($notification);
            } catch (\Throwable $e) {
                \Log::warning('Unable to send web push', [
                    'notification_id' => $notification->id,
                    'error' => $e->getMessage(),
                ]);
            }
        });
    }

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'json',
            'read_at' => 'datetime',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Helper methods
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    public function isRead()
    {
        return !is_null($this->read_at);
    }

    // Static methods for creating notifications
    public static function createTaskRejected($userId, $taskTitle, $reason, $submissionId)
    {
        $message = $reason;
        $message .= "\n\nJe kunt de lijst wel indienen. Als je manager om herhaling vraagt, moet je de taak opnieuw uitvoeren.";
        return self::create([
            'user_id' => $userId,
            'type' => 'task_rejected',
            'title' => "Je taak '{$taskTitle}' is afgewezen",
            'message' => $message,
            'data' => [
                'submission_id' => $submissionId,
                'task_title' => $taskTitle,
                'reason' => $reason,
            ],
        ]);
    }

    public static function createRedoRequested($userId, $taskTitle, $submissionId, $redoReason = null)
    {
        $message = $redoReason
            ? "Reden: {$redoReason}\n\nVoer deze taak opnieuw uit om de checklist te kunnen afronden."
            : "Voer deze taak opnieuw uit om de checklist te kunnen afronden.";
        return self::create([
            'user_id' => $userId,
            'type' => 'task_redo_requested',
            'title' => "Herhaal taak '{$taskTitle}'",
            'message' => $message,
            'data' => [
                'submission_id' => $submissionId,
                'task_title' => $taskTitle,
                'redo_reason' => $redoReason,
            ],
        ]);
    }

    public static function createTaskOverdue($userId, $taskTitle, $taskId, $listId = null)
    {
        // Check of er al een ongelezen notificatie bestaat voor deze taak vandaag
        $existingNotification = self::where('user_id', $userId)
            ->where('type', 'task_overdue')
            ->whereNull('read_at')
            ->whereDate('created_at', today())
            ->whereJsonContains('data->task_id', $taskId)
            ->first();

        // Als er al een notificatie bestaat, maak geen nieuwe
        if ($existingNotification) {
            return $existingNotification;
        }

        return self::create([
            'user_id' => $userId,
            'type' => 'task_overdue',
            'title' => 'Taak Te Laat',
            'message' => "De taak '{$taskTitle}' is te laat en moet nog worden afgerond",
            'data' => [
                'task_id' => $taskId,
                'task_title' => $taskTitle,
                'list_id' => $listId,
            ],
        ]);
    }
}
