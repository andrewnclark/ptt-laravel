<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Crm;

use App\Models\Crm\Company;
use App\Services\Crm\ContactService;
use Illuminate\View\View;
use Livewire\Component;

class AddContactModal extends Component
{
    /**
     * The company ID.
     *
     * @var int
     */
    public int $companyId;

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
        'is_primary' => false,
        'is_active' => true,
    ];

    /**
     * Whether the modal is open.
     *
     * @var bool
     */
    public bool $isOpen = false;

    /**
     * Validation rules.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'contact.first_name' => 'required|string|max:255',
            'contact.last_name' => 'required|string|max:255',
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
            'contact.first_name.required' => 'The contact first name is required.',
            'contact.last_name.required' => 'The contact last name is required.',
            'contact.email.email' => 'Please enter a valid email address for the contact.',
            'contact.phone.min' => 'Contact phone number must be at least 7 characters.',
            'contact.mobile.min' => 'Contact mobile number must be at least 7 characters.',
        ];
    }

    /**
     * Mount the component.
     *
     * @param int $companyId
     * @return void
     */
    public function mount(int $companyId): void
    {
        $this->companyId = $companyId;
    }

    /**
     * Open the modal.
     *
     * @return void
     */
    public function openModal(): void
    {
        $this->isOpen = true;
    }

    /**
     * Close the modal.
     *
     * @return void
     */
    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->reset('contact');
    }

    /**
     * Save the contact.
     *
     * @param ContactService $contactService
     * @return void
     */
    public function save(ContactService $contactService): void
    {
        $validatedData = $this->validate($this->rules());
        
        $company = Company::findOrFail($this->companyId);
        
        // Create the contact
        $contact = $contactService->createContactForCompany(
            $company, 
            $validatedData['contact'],
            $validatedData['contact']['is_primary'] ?? false
        );
        
        // Reset the form and close the modal
        $this->reset('contact');
        $this->closeModal();
        
        // Dispatch event to refresh the contacts list
        $this->dispatch('contact-added');
        
        // Show success message
        session()->flash('success', 'Contact added successfully.');
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.admin.crm.add-contact-modal');
    }
} 