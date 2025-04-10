<?php

declare(strict_types=1);

namespace App\Models\Crm;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'crm_activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'type',
        'description',
        'properties',
        'is_system_generated',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'properties' => 'array',
        'is_system_generated' => 'boolean',
    ];

    /**
     * Activity type constants.
     */
    public const TYPE_CREATED = 'created';
    public const TYPE_UPDATED = 'updated';
    public const TYPE_DELETED = 'deleted';
    public const TYPE_NOTE_ADDED = 'note_added';
    public const TYPE_EMAIL_SENT = 'email_sent';
    public const TYPE_TASK_CREATED = 'task_created';
    public const TYPE_TASK_COMPLETED = 'task_completed';
    public const TYPE_STATUS_CHANGED = 'status_changed';
    public const TYPE_STAGE_CHANGED = 'stage_changed';
    public const TYPE_CUSTOM = 'custom';

    /**
     * Get all activity type options.
     *
     * @return array<string, string>
     */
    public static function getTypeOptions(): array
    {
        return [
            self::TYPE_CREATED => 'Created',
            self::TYPE_UPDATED => 'Updated',
            self::TYPE_DELETED => 'Deleted',
            self::TYPE_NOTE_ADDED => 'Note Added',
            self::TYPE_EMAIL_SENT => 'Email Sent',
            self::TYPE_TASK_CREATED => 'Task Created',
            self::TYPE_TASK_COMPLETED => 'Task Completed',
            self::TYPE_STATUS_CHANGED => 'Status Changed',
            self::TYPE_STAGE_CHANGED => 'Stage Changed',
            self::TYPE_CUSTOM => 'Custom',
        ];
    }

    /**
     * Get the user that performed the activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject of the activity.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the causer of the activity.
     */
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope query to only include activities for a specific subject.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Model $subject
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForSubject($query, Model $subject)
    {
        return $query->where('subject_type', get_class($subject))
                    ->where('subject_id', $subject->getKey());
    }

    /**
     * Scope query to only include activities of a specific type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope query to only include system-generated activities.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSystemGenerated($query)
    {
        return $query->where('is_system_generated', true);
    }

    /**
     * Scope query to only include user-generated activities.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUserGenerated($query)
    {
        return $query->where('is_system_generated', false);
    }
} 