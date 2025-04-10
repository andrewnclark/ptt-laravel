<?php

declare(strict_types=1);

namespace App\Models\Crm;

use App\Traits\HasActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opportunity extends Model
{
    use HasFactory, SoftDeletes, HasActivities;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'crm_opportunities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'contact_id',
        'title',
        'description',
        'value',
        'status',
        'stage_id',
        'source',
        'expected_close_date',
        'actual_close_date',
        'probability',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'decimal:2',
        'probability' => 'integer',
        'expected_close_date' => 'date',
        'actual_close_date' => 'date',
    ];

    /**
     * Opportunity status constants.
     */
    public const STATUS_NEW = 'new';
    public const STATUS_QUALIFIED = 'qualified';
    public const STATUS_PROPOSAL = 'proposal';
    public const STATUS_NEGOTIATION = 'negotiation';
    public const STATUS_WON = 'won';
    public const STATUS_LOST = 'lost';

    /**
     * Opportunity source constants.
     */
    public const SOURCE_WEBSITE = 'website';
    public const SOURCE_REFERRAL = 'referral';
    public const SOURCE_COLD_CALL = 'cold_call';
    public const SOURCE_EVENT = 'event';
    public const SOURCE_SOCIAL_MEDIA = 'social_media';
    public const SOURCE_OTHER = 'other';

    /**
     * Get all status options.
     *
     * @return array<string, string>
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_QUALIFIED => 'Qualified',
            self::STATUS_PROPOSAL => 'Proposal',
            self::STATUS_NEGOTIATION => 'Negotiation',
            self::STATUS_WON => 'Won',
            self::STATUS_LOST => 'Lost',
        ];
    }

    /**
     * Get all source options.
     *
     * @return array<string, string>
     */
    public static function getSourceOptions(): array
    {
        return [
            self::SOURCE_WEBSITE => 'Website',
            self::SOURCE_REFERRAL => 'Referral',
            self::SOURCE_COLD_CALL => 'Cold Call',
            self::SOURCE_EVENT => 'Event',
            self::SOURCE_SOCIAL_MEDIA => 'Social Media',
            self::SOURCE_OTHER => 'Other',
        ];
    }

    /**
     * Get the company that owns the opportunity.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the contact that owns the opportunity.
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Get the stage that the opportunity is in.
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(OpportunityStage::class);
    }

    /**
     * Scope a query to only include open opportunities.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->whereNotIn('status', [self::STATUS_WON, self::STATUS_LOST]);
    }

    /**
     * Scope a query to only include won opportunities.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWon($query)
    {
        return $query->where('status', self::STATUS_WON);
    }

    /**
     * Scope a query to only include lost opportunities.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLost($query)
    {
        return $query->where('status', self::STATUS_LOST);
    }

    /**
     * Scope a query to filter by stage.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $stageId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInStage($query, int $stageId)
    {
        return $query->where('stage_id', $stageId);
    }

    /**
     * Move opportunity to a new stage.
     *
     * @param OpportunityStage $stage
     * @return bool
     */
    public function moveToStage(OpportunityStage $stage): bool
    {
        $oldStageId = $this->stage_id;
        
        // Update stage
        $this->stage_id = $stage->id;
        
        // If stage is a won stage, update status and close date
        if ($stage->is_won_stage) {
            $this->status = self::STATUS_WON;
            $this->actual_close_date = now();
        }
        
        // If stage is a lost stage, update status
        if ($stage->is_lost_stage) {
            $this->status = self::STATUS_LOST;
            $this->actual_close_date = now();
        }
        
        // Update probability based on stage
        $this->probability = $stage->probability;
        
        $saved = $this->save();
        
        // Record activity
        if ($saved && $oldStageId !== $stage->id) {
            $this->recordActivity(
                Activity::TYPE_STAGE_CHANGED,
                "Moved from " . ($oldStageId ? OpportunityStage::find($oldStageId)->name : 'No Stage') . " to {$stage->name}",
                [
                    'old_stage_id' => $oldStageId,
                    'new_stage_id' => $stage->id,
                ]
            );
        }
        
        return $saved;
    }
    
    /**
     * Record an activity for stage change.
     *
     * @param int|null $oldStageId
     * @param int $newStageId
     * @return Activity
     */
    public function getStageChangedActivity(?int $oldStageId, int $newStageId): Activity
    {
        $oldStageName = $oldStageId ? OpportunityStage::find($oldStageId)->name ?? 'Unknown Stage' : 'No Stage';
        $newStageName = OpportunityStage::find($newStageId)->name ?? 'Unknown Stage';
        
        return $this->recordActivity(
            Activity::TYPE_STAGE_CHANGED,
            "Moved from {$oldStageName} to {$newStageName}",
            [
                'old_stage_id' => $oldStageId,
                'new_stage_id' => $newStageId,
            ]
        );
    }

    /**
     * Get the formatted amount for the opportunity.
     *
     * @return string
     */
    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format((float) $this->value, 2);
    }
    
    /**
     * Get the closed_at date (alias for actual_close_date).
     *
     * @return \Illuminate\Support\Carbon|null
     */
    public function getClosedAtAttribute()
    {
        return $this->actual_close_date;
    }
} 