<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Crm;

use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Attributes\On;

class CompanyContactsList extends Component
{
    /**
     * The company ID.
     *
     * @var int
     */
    public int $companyId;

    /**
     * The company instance.
     *
     * @var Company
     */
    public Company $company;

    public ?int $contactToDelete = null;

    /**
     * Mount the component.
     *
     * @param int $companyId
     * @return void
     */
    public function mount(int $companyId): void
    {
        $this->companyId = $companyId;
        $this->loadCompany();
    }

    public function loadCompany()
    {
        $this->company = Company::with(['contacts' => function ($query) {
            $query->orderBy('is_primary', 'desc')
                  ->orderBy('first_name')
                  ->orderBy('last_name');
        }])->findOrFail($this->companyId);
    }

    /**
     * Listen for the contact-added event.
     *
     * @return void
     */
    #[On('contact-added')]
    public function refreshContacts(): void
    {
        $this->loadCompany();
    }

    public function promoteToPrimary(Contact $contact)
    {
        // First, remove primary status from current primary contact if exists
        if ($contact->company->contacts()->where('is_primary', true)->exists()) {
            $contact->company->contacts()->where('is_primary', true)->update(['is_primary' => false]);
        }

        // Set the new primary contact
        $contact->update(['is_primary' => true]);

        $this->loadCompany();
        $this->dispatch('notify', [
            'message' => 'Contact promoted to primary successfully.',
            'type' => 'success'
        ]);
    }

    public function confirmDelete(Contact $contact)
    {
        $this->contactToDelete = $contact->id;
    }

    public function cancelDelete()
    {
        $this->contactToDelete = null;
    }

    public function deleteContact()
    {
        $contact = Contact::findOrFail($this->contactToDelete);
        
        // If deleting primary contact, make the next contact primary
        if ($contact->is_primary) {
            $nextContact = $this->company->contacts()
                ->where('id', '!=', $contact->id)
                ->orderBy('first_name')
                ->first();
                
            if ($nextContact) {
                $nextContact->update(['is_primary' => true]);
            }
        }

        $contact->delete();
        $this->contactToDelete = null;
        $this->loadCompany();
        
        $this->dispatch('notify', [
            'message' => 'Contact deleted successfully.',
            'type' => 'success'
        ]);
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.admin.crm.company-contacts-list');
    }
} 