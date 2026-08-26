<?php

namespace App\Models\Organisation;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Checklist\ListAssignment;
use App\Models\Checklist\Task;
use App\Models\Checklist\TaskAssignment;
use App\Models\Checklist\TaskList;
use App\Models\Communication\DevicePushToken;
use App\Models\Communication\Notification;
use App\Models\Communication\PushSubscription;
use App\Models\Submissions\Submission;
use App\Models\Submissions\SubmissionTask;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\UserInvitationNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Password;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\BelongsToCompany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\Organisation\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'role',
        'phone',
        'department',
        'preferences',
        'is_active',
        'company_id',
        'location_id',
        'entra_object_id',
        'entra_tenant_id',
        'scim_external_id',
        'last_sso_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferences' => 'json',
            'is_active' => 'boolean',
            'last_sso_at' => 'datetime',
        ];
    }

    // Role-based helper methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function sendInvitationNotification(?User $invitedBy = null): void
    {
        $token = Password::createToken($this);

        $this->notify(new UserInvitationNotification(
            $token,
            $invitedBy?->name,
            $this->company?->name,
        ));
    }

    /**
     * Super admins are listed in SUPER_ADMIN_EMAILS (.env, comma-separated) and must be admin.
     */
    public function isSuperAdmin(): bool
    {
        if (!$this->isAdmin()) {
            return false;
        }

        $emails = collect(config('app.super_admin_emails', []))
            ->map(fn ($email) => strtolower(trim((string) $email)))
            ->filter();

        if ($emails->isEmpty()) {
            return false;
        }

        return $emails->contains(strtolower((string) $this->email));
    }

    /**
     * Primary dashboard URL for this user (marketing header, post-login, etc.).
     */
    public function homeDashboardUrl(): string
    {
        if ($this->isSuperAdmin()) {
            return route('super-admin.dashboard');
        }

        return route('dashboard');
    }

    // Relationships
    public function createdLists()
    {
        return $this->hasMany(TaskList::class, 'created_by');
    }

    public function assignments()
    {
        return $this->hasMany(ListAssignment::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function reviewedTasks()
    {
        return $this->hasMany(SubmissionTask::class, 'reviewed_by');
    }

    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function assignedTasks()
    {
        return $this->belongsToMany(Task::class, 'task_assignments')->withPivot('assigned_at', 'due_at', 'is_active')->withTimestamps();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function pushSubscriptions()
    {
        return $this->hasMany(PushSubscription::class);
    }

    public function devicePushTokens()
    {
        return $this->hasMany(DevicePushToken::class);
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->whereNull('read_at');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function sendPasswordResetNotification(#[\SensitiveParameter] $token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Scope a query to only include users from the current company
     */
    public function scopeForCurrentCompany($query)
    {
        if (auth()->check() && auth()->user()->company_id) {
            return $query->where('company_id', auth()->user()->company_id);
        }
        return $query;
    }
}
