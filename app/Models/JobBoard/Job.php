<?php

namespace App\Models\JobBoard;

use App\Models\Crm\Company;
use App\Models\JobBoard\JobCategory;
use App\Models\JobBoard\JobApplication;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'responsibilities',
        'requirements',
        'benefits',
        'category_id',
        'company_id',
        'location',
        'is_remote',
        'salary_min',
        'salary_max',
        'salary_currency',
        'employment_type',
        'experience_level',
        'status',
        'is_featured',
        'is_active',
        'published_at',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_remote' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    /**
     * Employment type options.
     */
    public const EMPLOYMENT_FULL_TIME = 'full-time';
    public const EMPLOYMENT_PART_TIME = 'part-time';
    public const EMPLOYMENT_CONTRACT = 'contract';
    public const EMPLOYMENT_TEMPORARY = 'temporary';
    public const EMPLOYMENT_INTERNSHIP = 'internship';
    public const EMPLOYMENT_FREELANCE = 'freelance';

    /**
     * Experience level options.
     */
    public const EXPERIENCE_ENTRY = 'entry';
    public const EXPERIENCE_JUNIOR = 'junior';
    public const EXPERIENCE_MID = 'mid';
    public const EXPERIENCE_SENIOR = 'senior';
    public const EXPERIENCE_LEAD = 'lead';
    public const EXPERIENCE_EXECUTIVE = 'executive';

    /**
     * Status options.
     */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CLOSED = 'closed';

    /**
     * Get all employment type options.
     *
     * @return array<string, string>
     */
    public static function getEmploymentTypeOptions(): array
    {
        return [
            self::EMPLOYMENT_FULL_TIME => 'Full Time',
            self::EMPLOYMENT_PART_TIME => 'Part Time',
            self::EMPLOYMENT_CONTRACT => 'Contract',
            self::EMPLOYMENT_TEMPORARY => 'Temporary',
            self::EMPLOYMENT_INTERNSHIP => 'Internship',
            self::EMPLOYMENT_FREELANCE => 'Freelance',
        ];
    }

    /**
     * Get all experience level options.
     *
     * @return array<string, string>
     */
    public static function getExperienceLevelOptions(): array
    {
        return [
            self::EXPERIENCE_ENTRY => 'Entry Level',
            self::EXPERIENCE_JUNIOR => 'Junior',
            self::EXPERIENCE_MID => 'Mid Level',
            self::EXPERIENCE_SENIOR => 'Senior',
            self::EXPERIENCE_LEAD => 'Lead / Manager',
            self::EXPERIENCE_EXECUTIVE => 'Executive',
        ];
    }

    /**
     * Get all status options.
     *
     * @return array<string, string>
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING => 'Pending Review',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_EXPIRED => 'Expired',
            self::STATUS_CLOSED => 'Closed',
        ];
    }

    /**
     * Get the formatted salary range.
     *
     * @return string
     */
    public function getSalaryRangeAttribute(): string
    {
        if (!$this->salary_min && !$this->salary_max) {
            return 'Not specified';
        }

        if ($this->salary_min && !$this->salary_max) {
            return "{$this->salary_currency} {$this->salary_min}+";
        }

        if (!$this->salary_min && $this->salary_max) {
            return "Up to {$this->salary_currency} {$this->salary_max}";
        }

        return "{$this->salary_currency} {$this->salary_min} - {$this->salary_max}";
    }

    /**
     * Get the company that owns the job.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the category that owns the job.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    /**
     * Get the applications for the job.
     *
     * @return HasMany
     */
    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Scope a query to only include active jobs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured jobs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include published jobs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
                    ->where('is_active', true)
                    ->where(function ($query) {
                        $query->whereNull('expires_at')
                              ->orWhere('expires_at', '>', now());
                    });
    }
} 