<?php

declare(strict_types=1);

namespace App\Services\Crm;

use App\Models\Crm\Activity;
use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use App\Models\Crm\Opportunity;
use App\Models\Crm\OpportunityStage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class OpportunityService
{
    /**
     * Get opportunities with filtering and pagination.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getOpportunities(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Opportunity::query()->with(['company', 'contact', 'stage']);

        // Apply filters
        if (isset($filters['company_id']) && $filters['company_id']) {
            $query->where('company_id', $filters['company_id']);
        }

        if (isset($filters['contact_id']) && $filters['contact_id']) {
            $query->where('contact_id', $filters['contact_id']);
        }

        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['stage_id']) && $filters['stage_id']) {
            $query->where('stage_id', $filters['stage_id']);
        }

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by expected close date range
        if (isset($filters['close_date_from']) && $filters['close_date_from']) {
            $query->where('expected_close_date', '>=', $filters['close_date_from']);
        }

        if (isset($filters['close_date_to']) && $filters['close_date_to']) {
            $query->where('expected_close_date', '<=', $filters['close_date_to']);
        }

        // Filter by value range
        if (isset($filters['value_min']) && $filters['value_min']) {
            $query->where('value', '>=', $filters['value_min']);
        }

        if (isset($filters['value_max']) && $filters['value_max']) {
            $query->where('value', '<=', $filters['value_max']);
        }

        // Apply sorting
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Get opportunities grouped by stage for pipeline view.
     *
     * @param array $filters
     * @return array
     */
    public function getPipelineOpportunities(array $filters = []): array
    {
        $stages = OpportunityStage::active()->ordered()->get();
        $result = [];

        foreach ($stages as $stage) {
            $query = Opportunity::with(['company', 'contact'])
                ->where('stage_id', $stage->id);

            // Apply filters
            if (isset($filters['company_id']) && $filters['company_id']) {
                $query->where('company_id', $filters['company_id']);
            }

            if (isset($filters['search']) && $filters['search']) {
                $search = $filters['search'];
                $query->where(function (Builder $q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhereHas('company', function (Builder $q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            }

            $result[$stage->id] = [
                'stage' => $stage,
                'opportunities' => $query->orderBy('expected_close_date')->get(),
                'total_value' => $query->sum('value'),
                'count' => $query->count(),
            ];
        }

        return $result;
    }

    /**
     * Create a new opportunity.
     *
     * @param array $data
     * @return Opportunity
     */
    public function createOpportunity(array $data): Opportunity
    {
        // Find the appropriate stage based on status if stage_id is not provided
        if (!isset($data['stage_id']) && isset($data['status'])) {
            $stageKey = match ($data['status']) {
                Opportunity::STATUS_NEW => 'new',
                Opportunity::STATUS_QUALIFIED => 'qualified',
                Opportunity::STATUS_PROPOSAL => 'proposal',
                Opportunity::STATUS_NEGOTIATION => 'negotiation',
                Opportunity::STATUS_WON => 'won',
                Opportunity::STATUS_LOST => 'lost',
                default => 'new',
            };
            
            $stage = OpportunityStage::where('key', $stageKey)->first();
            if ($stage) {
                $data['stage_id'] = $stage->id;
            }
        }
        
        return Opportunity::create($data);
    }

    /**
     * Update an existing opportunity.
     *
     * @param Opportunity $opportunity
     * @param array $data
     * @return bool
     */
    public function updateOpportunity(Opportunity $opportunity, array $data): bool
    {
        return $opportunity->update($data);
    }

    /**
     * Move an opportunity to a new stage.
     *
     * @param Opportunity $opportunity
     * @param int $stageId
     * @return bool
     */
    public function moveToStage(Opportunity $opportunity, int $stageId): bool
    {
        $stage = OpportunityStage::findOrFail($stageId);
        return $opportunity->moveToStage($stage);
    }

    /**
     * Mark an opportunity as won.
     *
     * @param Opportunity $opportunity
     * @param array $data
     * @return bool
     */
    public function markAsWon(Opportunity $opportunity, array $data = []): bool
    {
        $stage = OpportunityStage::won()->first();
        
        if (!$stage) {
            return false;
        }
        
        if (isset($data['actual_close_date'])) {
            $opportunity->actual_close_date = $data['actual_close_date'];
        } else {
            $opportunity->actual_close_date = now();
        }
        
        if (isset($data['value'])) {
            $opportunity->value = $data['value'];
        }
        
        return $opportunity->moveToStage($stage);
    }

    /**
     * Mark an opportunity as lost.
     *
     * @param Opportunity $opportunity
     * @param array $data
     * @return bool
     */
    public function markAsLost(Opportunity $opportunity, array $data = []): bool
    {
        $stage = OpportunityStage::lost()->first();
        
        if (!$stage) {
            return false;
        }
        
        if (isset($data['actual_close_date'])) {
            $opportunity->actual_close_date = $data['actual_close_date'];
        } else {
            $opportunity->actual_close_date = now();
        }
        
        if (isset($data['reason'])) {
            $opportunity->recordActivity(
                Activity::TYPE_CUSTOM,
                "Lost reason: {$data['reason']}",
                ['reason' => $data['reason']]
            );
        }
        
        return $opportunity->moveToStage($stage);
    }

    /**
     * Get opportunity by ID with relationships.
     *
     * @param int $id
     * @return Opportunity|null
     */
    public function getOpportunityById(int $id): ?Opportunity
    {
        return Opportunity::with(['company', 'contact', 'stage'])->find($id);
    }

    /**
     * Delete an opportunity.
     *
     * @param Opportunity $opportunity
     * @return bool
     */
    public function deleteOpportunity(Opportunity $opportunity): bool
    {
        return $opportunity->delete();
    }

    /**
     * Get all opportunity stages.
     *
     * @param bool $activeOnly
     * @return Collection
     */
    public function getAllStages(bool $activeOnly = true): Collection
    {
        $query = OpportunityStage::query()->ordered();
        
        if ($activeOnly) {
            $query->active();
        }
        
        return $query->get();
    }

    /**
     * Get pipeline summary statistics.
     *
     * @return array
     */
    public function getPipelineSummary(): array
    {
        $stages = $this->getAllStages();
        $result = [];
        $totalValue = 0;
        $totalCount = 0;
        
        foreach ($stages as $stage) {
            $value = Opportunity::where('stage_id', $stage->id)->sum('value');
            $count = Opportunity::where('stage_id', $stage->id)->count();
            
            $result[] = [
                'stage' => $stage,
                'value' => $value,
                'count' => $count,
            ];
            
            if (!$stage->is_won_stage && !$stage->is_lost_stage) {
                $totalValue += $value;
                $totalCount += $count;
            }
        }
        
        return [
            'stages' => $result,
            'total_value' => $totalValue,
            'total_count' => $totalCount,
            'won_value' => Opportunity::won()->sum('value'),
            'won_count' => Opportunity::won()->count(),
            'lost_value' => Opportunity::lost()->sum('value'),
            'lost_count' => Opportunity::lost()->count(),
        ];
    }
} 