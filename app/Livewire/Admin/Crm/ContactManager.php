<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Crm;

use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use App\Services\Crm\ContactService;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ContactManager extends Component
{
    use WithPagination;

    /**
     * Search term.
     *
     * @var string
     */
    public string $search = '';

    /**
     * Company filter.
     *
     * @var int|null
     */
    public ?int $companyId = null;

    /**
     * Active status filter.
     *
     * @var bool|null
     */
    public ?bool $isActive = null;

    /**
     * Sort field.
     *
     * @var string
     */
    public string $sortField = 'first_name';

    /**
     * Sort direction.
     *
     * @var string
     */
    public string $sortDirection = 'asc';

    /**
     * ID of the contact being deleted
     *
     * @var int|null
     */
    public ?int $deletingContactId = null;

    /**
     * Query string parameters
     *
     * @var array
     */
    protected $queryString = [
        'search' => ['except' => ''],
        'companyId' => ['except' => null],
        'isActive' => ['except' => null],
        'sortField' => ['except' => 'first_name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    /**
     * Reset the pagination when filters change
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCompanyId(): void
    {
        $this->resetPage();
    }

    public function updatedIsActive(): void
    {
        $this->resetPage();
    }

    /**
     * Sort by a given field.
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
     * Confirm contact deletion.
     *
     * @param int $contactId
     * @return void
     */
    public function confirmDelete(int $contactId): void
    {
        $this->deletingContactId = $contactId;
        $this->dispatch('open-modal', id: 'confirm-contact-deletion');
    }

    /**
     * Delete a contact.
     *
     * @return void
     */
    public function deleteContact(): void
    {
        if ($this->deletingContactId) {
            $contact = Contact::find($this->deletingContactId);
            
            if ($contact) {
                $contact->delete();
                session()->flash('success', 'Contact deleted successfully.');
            }
            
            $this->deletingContactId = null;
            $this->dispatch('close-modal', id: 'confirm-contact-deletion');
        }
    }

    /**
     * Cancel deletion.
     *
     * @return void
     */
    public function cancelDelete(): void
    {
        $this->deletingContactId = null;
        $this->dispatch('close-modal', id: 'confirm-contact-deletion');
    }

    /**
     * Render the component.
     *
     * @param ContactService $contactService
     * @return View
     */
    public function render(ContactService $contactService): View
    {
        $filters = [
            'search' => $this->search,
            'company_id' => $this->companyId,
            'is_active' => $this->isActive,
            'sort_field' => $this->sortField,
            'sort_direction' => $this->sortDirection,
        ];

        $contacts = $contactService->getPaginatedContacts($filters);
        $companies = Company::orderBy('name')->get();

        return view('livewire.admin.crm.contact-manager', [
            'contacts' => $contacts,
            'companies' => $companies,
        ]);
    }
} 