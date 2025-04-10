<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Crm;

use App\Models\Crm\Company;
use App\Services\Crm\CompanyService;
use App\Services\Crm\ContactService;
use Illuminate\View\View;
use Livewire\Component;

class CreateCompanyForm extends Component
{
    /**
     * Company data.
     *
     * @var array
     */
    public array $company = [
        'name' => '',
        'email' => '',
        'phone' => '',
        'website' => '',
        'industry' => '',
        'description' => '',
        'status' => 'lead',
        'is_active' => true,
    ];

    /**
     * Contact data.
     *
     * @var array
     */
    public array $contact = [
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'phone' => '',
        'mobile' => '',
        'job_title' => '',
        'department' => '',
        'notes' => '',
        'is_primary' => true,
        'is_active' => true,
    ];

    /**
     * Validation rules.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'company.name' => 'required|string|max:255',
            'company.email' => 'nullable|email|max:255',
            'company.phone' => 'nullable|string|min:7|max:50',
            'company.website' => 'nullable|url|max:255',
            'company.industry' => 'nullable|string|max:255',
            'company.description' => 'nullable|string',
            'company.status' => 'required|string|in:' . implode(',', array_keys(Company::getStatusOptions())),
            'company.is_active' => 'boolean',
            
            // Contact validation rules
            'contact.first_name' => 'required|string|max:255',
            'contact.last_name' => 'nullable|string|max:255',
            'contact.email' => 'nullable|email|max:255',
            'contact.phone' => 'nullable|string|min:7|max:50',
            'contact.mobile' => 'nullable|string|min:7|max:50',
            'contact.job_title' => 'nullable|string|max:255',
            'contact.department' => 'nullable|string|max:255',
            'contact.notes' => 'nullable|string',
            'contact.is_primary' => 'boolean',
            'contact.is_active' => 'boolean',
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
            'company.name.required' => 'The company name is required.',
            'company.email.email' => 'Please enter a valid email address.',
            'company.website.url' => 'Please enter a valid URL.',
            'company.phone.min' => 'Phone number must be at least 7 characters.',
            
            'contact.first_name.required' => 'The contact first name is required.',
            'contact.email.email' => 'Please enter a valid email address for the contact.',
            'contact.phone.min' => 'Contact phone number must be at least 7 characters.',
            'contact.mobile.min' => 'Contact mobile number must be at least 7 characters.',
        ];
    }

    /**
     * Reset the form fields.
     *
     * @return void
     */
    public function resetForm(): void
    {
        $this->reset('company', 'contact');
        $this->company['status'] = 'lead';
        $this->company['is_active'] = true;
        $this->contact['is_primary'] = true;
        $this->contact['is_active'] = true;
    }

    /**
     * Save the company and contact.
     *
     * @param CompanyService $companyService
     * @param ContactService $contactService
     * @return void
     */
    public function saveCompany(CompanyService $companyService, ContactService $contactService): void
    {
        $validatedData = $this->validate();
        
        try {
            // Start a database transaction
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            // Create the company
            $company = $companyService->createCompany($validatedData['company']);
            
            // Create the contact associated with the company
            $contactService->createContactForCompany($company, $validatedData['contact'], true);
            
            // Commit the transaction
            \Illuminate\Support\Facades\DB::commit();
            
            session()->flash('success', 'Company and primary contact created successfully.');
            $this->redirect(route('admin.crm.companies.index'));
        } catch (\Exception $e) {
            // Rollback the transaction on error
            \Illuminate\Support\Facades\DB::rollBack();
            
            // Log the error
            \Illuminate\Support\Facades\Log::error('Error creating company with contact', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            session()->flash('error', 'Error creating company: ' . $e->getMessage());
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
        
        return view('livewire.admin.crm.create-company-form', [
            'statusOptions' => $statusOptions,
        ]);
    }
}
