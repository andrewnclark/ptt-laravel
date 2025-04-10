<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Crm;

use App\Models\Crm\Company;
use App\Services\Crm\CompanyService;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyManager extends Component
{
    use WithPagination;

    /**
     * Search term.
     *
     * @var string
     */
    public string $search = '';

    /**
     * Status filter.
     *
     * @var string
     */
    public string $status = '';

    /**
     * Trashed filter.
     *
     * @var string
     */
    public string $trashedFilter = 'exclude';

    /**
     * Sort field.
     *
     * @var string
     */
    public string $sortField = 'name';

    /**
     * Sort direction.
     *
     * @var string
     */
    public string $sortDirection = 'asc';

    /**
     * Company being deleted.
     *
     * @var Company|null
     */
    public ?Company $deletingCompany = null;

    /**
     * Company being force deleted.
     *
     * @var int|null
     */
    public ?int $forceDeleteCompanyId = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->trashedFilter = 'exclude';
    }

    /**
     * Reset the pagination when filters change.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset the pagination when status changes.
     */
    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    /**
     * Reset the pagination when trashed filter changes.
     */
    public function updatedTrashedFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Sort by the given field.
     *
     * @param string $field
     * @return void
     */
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Set the company to be deleted.
     *
     * @param int $companyId
     * @param CompanyService $companyService
     * @return void
     */
    public function confirmCompanyDeletion(int $companyId, CompanyService $companyService): void
    {
        $this->deletingCompany = $companyService->getCompanyById($companyId);
    }

    /**
     * Delete the company.
     *
     * @param CompanyService $companyService
     * @return void
     */
    public function deleteCompany(CompanyService $companyService): void
    {
        if ($this->deletingCompany) {
            $companyService->deleteCompany($this->deletingCompany);
            $this->deletingCompany = null;
            session()->flash('success', 'Company deleted successfully.');
        }
    }

    /**
     * Cancel company deletion.
     *
     * @return void
     */
    public function cancelDeletion(): void
    {
        $this->deletingCompany = null;
    }

    /**
     * Toggle company deleted state (soft delete/restore).
     *
     * @param int $companyId
     * @param CompanyService $companyService
     * @return void
     */
    public function toggleDeletedState(int $companyId, CompanyService $companyService): void
    {
        try {
            // Find the company with trashed records
            $company = Company::withTrashed()->findOrFail($companyId);
            
            // Debug log
            \Illuminate\Support\Facades\Log::info('Toggle deleted state', [
                'company_id' => $companyId,
                'is_trashed' => $company->trashed(),
                'company_name' => $company->name,
                'filter_mode' => $this->trashedFilter
            ]);
            
            // Soft delete the company
            $companyService->deleteCompany($company);
            session()->flash('success', 'Company moved to trash successfully.');
            
            // Force reset the page to first page
            $this->resetPage();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error trashing company', [
                'company_id' => $companyId,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', 'Error trashing company: ' . $e->getMessage());
        }
    }

    /**
     * Change company status.
     *
     * @param int $companyId
     * @param string $status
     * @param CompanyService $companyService
     * @return void
     */
    public function changeStatus(int $companyId, string $status, CompanyService $companyService): void
    {
        $company = $companyService->getCompanyById($companyId);
        if ($company) {
            $companyService->changeStatus($company, $status);
            session()->flash('success', 'Company status changed successfully.');
        }
    }

    /**
     * Confirm force deletion of a company.
     *
     * @param int $companyId
     * @return void
     */
    public function confirmForceDelete(int $companyId): void
    {
        $this->forceDeleteCompanyId = $companyId;
    }

    /**
     * Cancel force deletion.
     *
     * @return void
     */
    public function cancelForceDelete(): void
    {
        $this->forceDeleteCompanyId = null;
    }

    /**
     * Force delete a company permanently.
     *
     * @param CompanyService $companyService
     * @return void
     */
    public function forceDeleteCompany(CompanyService $companyService): void
    {
        if ($this->forceDeleteCompanyId) {
            $companyService->forceDeleteCompany($this->forceDeleteCompanyId);
            $this->forceDeleteCompanyId = null;
            session()->flash('success', 'Company permanently deleted.');
        }
    }

    /**
     * Restore a soft-deleted company.
     *
     * @param int $companyId
     * @param CompanyService $companyService
     * @return void
     */
    public function restoreCompany(int $companyId, CompanyService $companyService): void
    {
        $company = $companyService->restoreCompany($companyId);
        
        if ($company) {
            session()->flash('success', 'Company restored successfully.');
        } else {
            session()->flash('error', 'Failed to restore company.');
        }
    }

    /**
     * Directly restore a company with special handling for last trashed item.
     *
     * @param int $companyId
     * @param bool $isLastItem
     * @return void
     */
    public function directRestore(int $companyId, bool $isLastItem = false): void
    {
        try {
            // Find the company with trashed records
            $company = Company::withTrashed()->findOrFail($companyId);
            
            if ($company->trashed()) {
                // Direct restore on the model
                $company->restore();
                
                // The CompanyObserver will automatically record the activity
                // No need to manually record it here
                
                // Check if we need to switch filter mode for last item
                if ($isLastItem && $this->trashedFilter === 'only') {
                    $this->trashedFilter = 'include';
                }
                
                // Success message
                session()->flash('success', 'Company restored successfully.');
                
                // Force reset the page to first page to ensure we see the restored company
                $this->resetPage();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error restoring company', [
                'company_id' => $companyId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error restoring company: ' . $e->getMessage());
        }
    }

    /**
     * Render the component.
     *
     * @param CompanyService $companyService
     * @return View
     */
    public function render(CompanyService $companyService): View
    {
        $filters = [
            'search' => $this->search,
            'status' => $this->status,
            'sort_field' => $this->sortField,
            'sort_direction' => $this->sortDirection,
        ];

        // Handle trashed companies based on filter
        if ($this->trashedFilter === 'include') {
            $filters['with_trashed'] = true;
        } elseif ($this->trashedFilter === 'only') {
            $filters['only_trashed'] = true;
        }

        $companies = $companyService->getPaginatedCompanies($filters);
        $statusOptions = Company::getStatusOptions();
        
        // Count trashed companies for the UI
        $trashedCount = Company::onlyTrashed()->count();

        return view('livewire.admin.crm.company-manager', [
            'companies' => $companies,
            'statusOptions' => $statusOptions,
            'trashedCount' => $trashedCount,
        ]);
    }
} 