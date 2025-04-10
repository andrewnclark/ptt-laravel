<?php

namespace App\Models\Ats;

use App\Models\JobBoard\JobApplication;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'headline',
        'summary',
        'resume_path',
        'linkedin_url',
        'website_url',
        'github_url',
        'status',
        'source',
        'notes',
        'skills',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'skills' => 'array',
    ];

    /**
     * Candidate status options.
     */
    public const STATUS_NEW = 'new';
    public const STATUS_SCREENING = 'screening';
    public const STATUS_INTERVIEWING = 'interviewing';
    public const STATUS_OFFERED = 'offered';
    public const STATUS_HIRED = 'hired';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_BLACKLISTED = 'blacklisted';
    public const STATUS_ARCHIVED = 'archived';

    /**
     * Get all status options.
     *
     * @return array<string, string>
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_SCREENING => 'Screening',
            self::STATUS_INTERVIEWING => 'Interviewing',
            self::STATUS_OFFERED => 'Offered',
            self::STATUS_HIRED => 'Hired',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_BLACKLISTED => 'Blacklisted',
            self::STATUS_ARCHIVED => 'Archived',
        ];
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
     * Get the job applications for the candidate.
     *
     * @return HasMany
     */
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Scope a query to only include active candidates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
} 