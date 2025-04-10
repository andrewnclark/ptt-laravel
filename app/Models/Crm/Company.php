<?php

declare(strict_types=1);

namespace App\Models\Crm;

use App\Models\JobBoard\Job;
use App\Traits\HasActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes, HasActivities;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'crm_companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'logo',
        'description',
        'industry',
        'website',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     * Status options.
     */
    public const STATUS_LEAD = 'lead';
    public const STATUS_PROSPECT = 'prospect';
    public const STATUS_CUSTOMER = 'customer';
    public const STATUS_CHURNED = 'churned';

    /**
     * Get all status options.
     *
     * @return array<string, string>
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_LEAD => 'Lead',
            self::STATUS_PROSPECT => 'Prospect',
            self::STATUS_CUSTOMER => 'Customer',
            self::STATUS_CHURNED => 'Churned',
        ];
    }

    /**
     * Get the contacts for the company.
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * Get the opportunities for the company.
     */
    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class);
    }

    /**
     * Get the jobs for the company.
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Get the active jobs count.
     *
     * @return int
     */
    public function getActiveJobsCountAttribute(): int
    {
        return $this->jobs()->published()->count();
    }

    /**
     * Get the tasks for the company.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Change company status and record activity.
     *
     * @param string $status
     * @return bool
     */
    public function changeStatus(string $status): bool
    {
        $oldStatus = $this->status;
        
        if ($oldStatus === $status) {
            return true;
        }
        
        $this->status = $status;
        $saved = $this->save();
        
        if ($saved) {
            $this->recordActivity(
                Activity::TYPE_STATUS_CHANGED,
                "Status changed from " . self::getStatusOptions()[$oldStatus] . " to " . self::getStatusOptions()[$status],
                [
                    'old_status' => $oldStatus,
                    'new_status' => $status,
                ]
            );
        }
        
        return $saved;
    }

    /**
     * Scope a query to only include active companies.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope a query to only include companies with a specific status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->whereNull('deleted_at')->where('status', $status);
    }

    /**
     * Scope a query to only include client companies.
     */
    public function scopeClients($query)
    {
        return $query->whereNull('deleted_at')->where('status', self::STATUS_CUSTOMER);
    }

    /**
     * Scope a query to only include lead companies.
     */
    public function scopeLeads($query)
    {
        return $query->whereNull('deleted_at')->where('status', self::STATUS_LEAD);
    }

    /**
     * Scope a query to only include prospect companies.
     */
    public function scopeProspects($query)
    {
        return $query->whereNull('deleted_at')->where('status', self::STATUS_PROSPECT);
    }
} 