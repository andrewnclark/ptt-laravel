<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Crm\Activity;
use App\Models\Crm\Company;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CompanyObserver
{
    /**
     * Handle the Company "created" event.
     */
    public function created(Company $company): void
    {
        Log::info('CompanyObserver: Company created', [
            'company_id' => $company->id,
            'company_name' => $company->name
        ]);

        try {
            $company->activities()->create([
                'user_id' => Auth::id() ?? 1,
                'type' => Activity::TYPE_CREATED,
                'description' => 'Created company: ' . $company->name,
                'properties' => ['attributes' => $company->attributesToArray()],
                'is_system_generated' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('CompanyObserver: Failed to record creation activity', [
                'company_id' => $company->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle the Company "updated" event.
     */
    public function updated(Company $company): void
    {
        $dirty = $company->getDirty();
        $filteredDirty = Arr::except($dirty, ['updated_at', 'created_at']);
        
        Log::info('CompanyObserver: Company updated', [
            'company_id' => $company->id,
            'company_name' => $company->name,
            'changes' => $filteredDirty,
            'has_changes' => !empty($filteredDirty)
        ]);

        if (!empty($filteredDirty)) {
            $original = $company->getOriginal();
            $changes = [];
            $old = [];
            
            foreach ($filteredDirty as $key => $value) {
                $changes[$key] = $value;
                $old[$key] = $original[$key] ?? null;
            }
            
            try {
                // Use the recordActivity method from the trait which has duplicate prevention
                $company->recordActivity(
                    Activity::TYPE_UPDATED,
                    'Updated company: ' . $company->name,
                    [
                        'old' => $old,
                        'new' => $changes,
                    ]
                );
                
                // Special handling for status changes
                if (isset($changes['status']) && isset($old['status']) && $changes['status'] !== $old['status']) {
                    $oldStatus = $old['status'];
                    $newStatus = $changes['status'];
                    
                    // Use the recordActivity method from the trait which has duplicate prevention
                    $company->recordActivity(
                        Activity::TYPE_STATUS_CHANGED,
                        "Status changed from " . 
                            Company::getStatusOptions()[$oldStatus] . " to " . 
                            Company::getStatusOptions()[$newStatus],
                        [
                            'old_status' => $oldStatus,
                            'new_status' => $newStatus,
                        ]
                    );
                }
            } catch (\Exception $e) {
                Log::error('CompanyObserver: Failed to record update activity', [
                    'company_id' => $company->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
    }

    /**
     * Handle the Company "deleted" event.
     */
    public function deleted(Company $company): void
    {
        Log::info('CompanyObserver: Company deleted', [
            'company_id' => $company->id,
            'company_name' => $company->name
        ]);

        try {
            $company->activities()->create([
                'user_id' => Auth::id() ?? 1,
                'type' => Activity::TYPE_DELETED,
                'description' => 'Deleted company: ' . $company->name,
                'properties' => ['attributes' => $company->attributesToArray()],
                'is_system_generated' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('CompanyObserver: Failed to record deletion activity', [
                'company_id' => $company->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle the Company "restored" event.
     */
    public function restored(Company $company): void
    {
        Log::info('CompanyObserver: Company restored', [
            'company_id' => $company->id,
            'company_name' => $company->name
        ]);

        try {
            $company->activities()->create([
                'user_id' => Auth::id() ?? 1,
                'type' => 'restored',
                'description' => 'Restored company: ' . $company->name,
                'properties' => ['attributes' => $company->attributesToArray()],
                'is_system_generated' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('CompanyObserver: Failed to record restoration activity', [
                'company_id' => $company->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle the Company "force deleted" event.
     */
    public function forceDeleted(Company $company): void
    {
        Log::info('CompanyObserver: Company force deleted', [
            'company_id' => $company->id,
            'company_name' => $company->name
        ]);

        try {
            $company->activities()->create([
                'user_id' => Auth::id() ?? 1,
                'type' => 'force_deleted',
                'description' => 'Permanently deleted company: ' . $company->name,
                'properties' => ['attributes' => $company->attributesToArray()],
                'is_system_generated' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('CompanyObserver: Failed to record force deletion activity', [
                'company_id' => $company->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
} 