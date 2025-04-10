<?php

declare(strict_types=1);

namespace App\Services\Crm;

use App\Models\Crm\Activity;
use App\Models\Crm\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyService
{
    /**
     * Get all companies with optional filtering.
     *
     * @param array $filters
     * @return Collection
     */
    public function getAllCompanies(array $filters = []): Collection
    {
        $query = Company::query();

        // Apply filters
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['is_active']) && is_bool($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('industry', 'like', "%{$search}%");
            });
        }

        // Apply ordering
        $sortField = $filters['sort_field'] ?? 'name';
        $sortDirection = $filters['sort_direction'] ?? 'asc';
        $query->orderBy($sortField, $sortDirection);

        return $query->with('contacts')->get();
    }

    /**
     * Get paginated companies with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedCompanies(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Company::query();

        // Check if we should include trashed companies
        if (isset($filters['with_trashed']) && $filters['with_trashed']) {
            $query->withTrashed();
        }
        
        // Check if we should only show trashed companies
        if (isset($filters['only_trashed']) && $filters['only_trashed']) {
            $query->onlyTrashed();
        }

        // Apply filters
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('industry', 'like', "%{$search}%");
            });
        }

        // Apply ordering
        $sortField = $filters['sort_field'] ?? 'name';
        $sortDirection = $filters['sort_direction'] ?? 'asc';
        $query->orderBy($sortField, $sortDirection);

        return $query->with('contacts')->paginate($perPage);
    }

    /**
     * Get active clients.
     *
     * @return Collection
     */
    public function getActiveClients(): Collection
    {
        return Company::active()->clients()->orderBy('name')->get();
    }

    /**
     * Get leads.
     *
     * @return Collection
     */
    public function getLeads(): Collection
    {
        return Company::active()->leads()->orderBy('name')->get();
    }

    /**
     * Get prospects.
     *
     * @return Collection
     */
    public function getProspects(): Collection
    {
        return Company::active()->prospects()->orderBy('name')->get();
    }

    /**
     * Create a new company.
     *
     * @param array $data
     * @return Company
     */
    public function createCompany(array $data): Company
    {
        $company = Company::create($data);
        // Activity recording is now handled by CompanyObserver
        return $company;
    }

    /**
     * Update an existing company.
     *
     * @param Company $company
     * @param array $data
     * @return Company
     */
    public function updateCompany(Company $company, array $data): Company
    {
        // Keep track of original values for logging purposes only
        $originalValues = $company->getOriginal();
        
        // Log before update
        \Illuminate\Support\Facades\Log::debug('CompanyService::updateCompany before update', [
            'company_id' => $company->id,
            'original' => $originalValues,
            'update_data' => $data
        ]);
        
        $company->update($data);
        
        // Log after update
        \Illuminate\Support\Facades\Log::debug('CompanyService::updateCompany after update', [
            'company_id' => $company->id,
            'changes' => $company->getChanges(),
            'was_dirty' => $company->isDirty(),
            'was_changed' => $company->wasChanged()
        ]);
        
        // Activity recording is now handled by CompanyObserver
        return $company;
    }

    /**
     * Delete a company.
     *
     * @param Company $company
     * @return bool
     */
    public function deleteCompany(Company $company): bool
    {
        // Observer will handle activity recording automatically
        
        // Use delete() which triggers the SoftDeletes trait
        return $company->delete();
    }

    /**
     * Get company by ID.
     *
     * @param int $id
     * @param bool $withTrashed Whether to include trashed companies
     * @return Company|null
     */
    public function getCompanyById(int $id, bool $withTrashed = true): ?Company
    {
        $query = Company::query();
        
        if ($withTrashed) {
            $query->withTrashed();
        }
        
        return $query->with(['contacts', 'opportunities'])->find($id);
    }

    /**
     * Change company status.
     *
     * @param Company $company
     * @param string $status
     * @return Company
     */
    public function changeStatus(Company $company, string $status): Company
    {
        // Use the model's changeStatus method which handles activity recording
        $company->changeStatus($status);
        return $company;
    }

    /**
     * Restore a soft-deleted company.
     *
     * @param int|Company $company
     * @return Company|null
     */
    public function restoreCompany($company): ?Company
    {
        // If we got an ID, find the company
        if (is_numeric($company)) {
            $company = Company::withTrashed()->find($company);
        }
        
        // Debug log
        \Illuminate\Support\Facades\Log::debug('CompanyService::restoreCompany', [
            'company_id' => $company->id ?? null,
            'company_name' => $company->name ?? 'unknown',
            'is_trashed' => $company && $company->trashed(),
        ]);
        
        if ($company && $company->trashed()) {
            try {
                $company->restore();
                
                // Observer will handle activity recording
                
                \Illuminate\Support\Facades\Log::info('Company restored successfully', [
                    'company_id' => $company->id,
                    'company_name' => $company->name
                ]);
                
                return $company;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to restore company', [
                    'company_id' => $company->id,
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            \Illuminate\Support\Facades\Log::warning('Attempted to restore non-trashed company', [
                'company_id' => $company->id ?? null,
                'found' => $company ? 'true' : 'false',
                'trashed' => $company && $company->trashed() ? 'true' : 'false'
            ]);
        }
        
        return null;
    }
    
    /**
     * Permanently delete a company.
     *
     * @param int $id
     * @return bool
     */
    public function forceDeleteCompany(int $id): bool
    {
        $company = Company::withTrashed()->find($id);
        
        if ($company) {
            // Observer will handle activity recording automatically via forceDeleted method
            
            return $company->forceDelete();
        }
        
        return false;
    }
    
    /**
     * Get trashed companies with optional filtering.
     *
     * @param array $filters
     * @return Collection
     */
    public function getTrashedCompanies(array $filters = []): Collection
    {
        $query = Company::onlyTrashed();
        
        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        return $query->get();
    }
} 