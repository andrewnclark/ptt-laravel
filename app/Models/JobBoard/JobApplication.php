<?php

namespace App\Models\JobBoard;

use App\Models\JobBoard\Job;
use App\Models\Ats\Candidate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_id',
        'candidate_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'cover_letter',
        'resume_path',
        'status',
        'source',
        'notes',
        'ip_address',
        'user_agent',
        'viewed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    /**
     * Application status options.
     */
    public const STATUS_NEW = 'new';
    public const STATUS_REVIEWING = 'reviewing';
    public const STATUS_SHORTLISTED = 'shortlisted';
    public const STATUS_INTERVIEWING = 'interviewing';
    public const STATUS_OFFERED = 'offered';
    public const STATUS_HIRED = 'hired';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_WITHDRAWN = 'withdrawn';

    /**
     * Get all status options.
     *
     * @return array<string, string>
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_REVIEWING => 'Reviewing',
            self::STATUS_SHORTLISTED => 'Shortlisted',
            self::STATUS_INTERVIEWING => 'Interviewing',
            self::STATUS_OFFERED => 'Offered',
            self::STATUS_HIRED => 'Hired',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_WITHDRAWN => 'Withdrawn',
        ];
    }

    /**
     * Get the job that owns the application.
     *
     * @return BelongsTo
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get the candidate that owns the application.
     *
     * @return BelongsTo
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the full name attribute.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Scope a query to only include new applications.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }
    
    /**
     * Scope a query to only include active applications (not rejected or withdrawn).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_REJECTED, self::STATUS_WITHDRAWN]);
    }
    
    /**
     * Mark the application as viewed.
     *
     * @return bool
     */
    public function markAsViewed(): bool
    {
        if (!$this->viewed_at) {
            $this->viewed_at = now();
            return $this->save();
        }
        
        return false;
    }
} 