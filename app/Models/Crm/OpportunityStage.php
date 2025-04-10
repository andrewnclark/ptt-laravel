<?php

declare(strict_types=1);

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpportunityStage extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'crm_opportunity_stages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'key',
        'description',
        'position',
        'probability',
        'color',
        'is_active',
        'is_won_stage',
        'is_lost_stage',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'position' => 'integer',
        'probability' => 'decimal:2',
        'is_active' => 'boolean',
        'is_won_stage' => 'boolean',
        'is_lost_stage' => 'boolean',
    ];

    /**
     * Get the opportunities in this stage.
     */
    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class, 'stage_id');
    }

    /**
     * Scope a query to only include active stages.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include won stages.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWon($query)
    {
        return $query->where('is_won_stage', true);
    }

    /**
     * Scope a query to only include lost stages.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLost($query)
    {
        return $query->where('is_lost_stage', true);
    }

    /**
     * Scope a query to only include open stages (not won or lost).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->where('is_won_stage', false)
                    ->where('is_lost_stage', false);
    }

    /**
     * Scope a query to order by position.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position', 'asc');
    }
} 