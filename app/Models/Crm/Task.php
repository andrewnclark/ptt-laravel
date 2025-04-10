<?php

declare(strict_types=1);

namespace App\Models\Crm;

use App\Models\User;
use App\Traits\HasActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes, HasActivities;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'crm_tasks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'status',
        'priority',
        'company_id',
        'contact_id',
        'opportunity_id',
        'user_id',
        'assigned_to',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Task status constants.
     */
    public const STATUS_NOT_STARTED = 'not_started';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_DEFERRED = 'deferred';
    public const STATUS_WAITING = 'waiting';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Task priority constants.
     */
    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_URGENT = 'urgent';

    /**
     * Get all status options.
     *
     * @return array<string, string>
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_NOT_STARTED => 'Not Started',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_DEFERRED => 'Deferred',
            self::STATUS_WAITING => 'Waiting',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    /**
     * Get all priority options.
     *
     * @return array<string, string>
     */
    public static function getPriorityOptions(): array
    {
        return [
            self::PRIORITY_LOW => 'Low',
            self::PRIORITY_MEDIUM => 'Medium',
            self::PRIORITY_HIGH => 'High',
            self::PRIORITY_URGENT => 'Urgent',
        ];
    }

    /**
     * Get the user that created the task.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user that the task is assigned to.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the company that the task is for.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the contact that the task is for.
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Get the opportunity that the task is for.
     */
    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }

    /**
     * Mark the task as completed.
     *
     * @return bool
     */
    public function complete(): bool
    {
        if ($this->status === self::STATUS_COMPLETED) {
            return true;
        }
        
        $this->status = self::STATUS_COMPLETED;
        $this->completed_at = now();
        $saved = $this->save();
        
        if ($saved) {
            $this->recordActivity(
                Activity::TYPE_TASK_COMPLETED,
                "Task completed: {$this->title}",
                [
                    'task_id' => $this->id,
                    'completed_at' => $this->completed_at,
                ]
            );
            
            // Record activity on related entities
            if ($this->company_id) {
                $this->company->recordActivity(
                    Activity::TYPE_TASK_COMPLETED,
                    "Task completed: {$this->title}",
                    [
                        'task_id' => $this->id,
                        'completed_at' => $this->completed_at,
                    ]
                );
            }
            
            if ($this->contact_id) {
                $this->contact->recordActivity(
                    Activity::TYPE_TASK_COMPLETED,
                    "Task completed: {$this->title}",
                    [
                        'task_id' => $this->id,
                        'completed_at' => $this->completed_at,
                    ]
                );
            }
            
            if ($this->opportunity_id) {
                $this->opportunity->recordActivity(
                    Activity::TYPE_TASK_COMPLETED,
                    "Task completed: {$this->title}",
                    [
                        'task_id' => $this->id,
                        'completed_at' => $this->completed_at,
                    ]
                );
            }
        }
        
        return $saved;
    }

    /**
     * Scope a query to only include tasks with a specific status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include incomplete tasks.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIncomplete($query)
    {
        return $query->where('status', '!=', self::STATUS_COMPLETED)
                    ->where('status', '!=', self::STATUS_CANCELLED);
    }

    /**
     * Scope a query to only include completed tasks.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope a query to only include overdue tasks.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', '!=', self::STATUS_COMPLETED)
                    ->where('status', '!=', self::STATUS_CANCELLED);
    }

    /**
     * Scope a query to only include tasks due today.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', now()->toDateString())
                    ->where('status', '!=', self::STATUS_COMPLETED)
                    ->where('status', '!=', self::STATUS_CANCELLED);
    }

    /**
     * Scope a query to only include tasks with a specific priority.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $priority
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to only include tasks assigned to a specific user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }
} 