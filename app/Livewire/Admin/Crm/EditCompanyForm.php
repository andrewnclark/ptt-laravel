<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Crm;

use App\Models\Crm\Company;
use App\Services\Crm\CompanyService;
use Illuminate\View\View;
use Livewire\Component;

class EditCompanyForm extends Component
{
    /**
     * The company being edited.
     *
     * @var Company
     */
    public Company $companyModel;

    /**
     * Company data.
     *
     * @var array
     */
    public array $companyData = [
        'name' => '',
        'email' => '',
        'phone' => '',
        'website' => '',
        'industry' => '',
        'description' => '',
        'status' => '',
        'is_active' => true,
    ];

    /**
     * Original status before update.
     *
     * @var string|null
     */
    private ?string $originalStatus = null;

    /**
     * Mount the component.
     *
     * @param Company $company
     * @return void
     */
    public function mount(Company $company): void
    {
        $this->companyModel = $company;
        $this->originalStatus = $company->status;
        
        $this->companyData = [
            'name' => $company->name,
            'email' => $company->email ?? '',
            'phone' => $company->phone ?? '',
            'website' => $company->website ?? '',
            'industry' => $company->industry ?? '',
            'description' => $company->description ?? '',
            'status' => $company->status,
            'is_active' => $company->is_active,
        ];
    }

    /**
     * Validation rules.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'companyData.name' => 'required|string|max:255',
            'companyData.email' => 'nullable|email|max:255',
            'companyData.phone' => 'nullable|string|min:7|max:50',
            'companyData.website' => 'nullable|url|max:255',
            'companyData.industry' => 'nullable|string|max:255',
            'companyData.description' => 'nullable|string',
            'companyData.status' => 'required|string|in:' . implode(',', array_keys(Company::getStatusOptions())),
            'companyData.is_active' => 'boolean',
        ];
    }

    /**
     * Validation messages.
     *
     * @return array
     */
    protected function messages(): array
    {
        return [
            'companyData.name.required' => 'The company name is required.',
            'companyData.email.email' => 'Please enter a valid email address.',
            'companyData.website.url' => 'Please enter a valid URL.',
            'companyData.phone.min' => 'Phone number must be at least 7 characters.',
        ];
    }

    /**
     * Save the company changes.
     *
     * @param CompanyService $companyService
     * @return void
     */
    public function saveCompany(CompanyService $companyService): void
    {
        $validatedData = $this->validate();
        
        try {
            // Check if only status has changed
            $statusChanged = $this->originalStatus !== $validatedData['companyData']['status'];
            $onlyStatusChanged = $statusChanged && count(array_diff_assoc($validatedData['companyData'], [
                'status' => $validatedData['companyData']['status'],
                'name' => $this->companyModel->name,
                'email' => $this->companyModel->email ?? '',
                'phone' => $this->companyModel->phone ?? '',
                'website' => $this->companyModel->website ?? '',
                'industry' => $this->companyModel->industry ?? '',
                'description' => $this->companyModel->description ?? '',
                'is_active' => $this->companyModel->is_active,
            ])) === 0;
            
            // If only the status has changed, use changeStatus method directly
            if ($onlyStatusChanged) {
                $this->companyModel->changeStatus($validatedData['companyData']['status']);
                $company = $this->companyModel;
            } else {
                // Otherwise update all fields - the status change will be captured by the observer
                $company = $companyService->updateCompany($this->companyModel, $validatedData['companyData']);
            }
            
            session()->flash('success', 'Company updated successfully.');
            $this->redirect(route('admin.crm.companies.show', $company->id));
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating company: ' . $e->getMessage());
        }
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render(): View
    {
        $statusOptions = Company::getStatusOptions();
        
        return view('livewire.admin.crm.edit-company-form', [
            'statusOptions' => $statusOptions,
        ]);
    }
}
