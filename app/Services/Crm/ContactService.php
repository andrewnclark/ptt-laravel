<?php

declare(strict_types=1);

namespace App\Services\Crm;

use App\Models\Crm\Contact;
use App\Models\Crm\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ContactService
{
    /**
     * Get all contacts for a specific company.
     *
     * @param int $companyId
     * @return Collection
     */
    public function getContactsByCompany(int $companyId): Collection
    {
        return Contact::where('company_id', $companyId)
            ->orderBy('is_primary', 'desc')
            ->orderBy('first_name')
            ->get();
    }

    /**
     * Get paginated contacts with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedContacts(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Contact::query();

        // Check if we should include trashed contacts
        if (isset($filters['with_trashed']) && $filters['with_trashed']) {
            $query->withTrashed();
        }
        
        // Check if we should only show trashed contacts
        if (isset($filters['only_trashed']) && $filters['only_trashed']) {
            $query->onlyTrashed();
        }

        // Filter by company
        if (isset($filters['company_id']) && $filters['company_id']) {
            $query->where('company_id', $filters['company_id']);
        }

        // Search filter
        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Apply ordering
        $sortField = $filters['sort_field'] ?? 'first_name';
        $sortDirection = $filters['sort_direction'] ?? 'asc';
        $query->orderBy($sortField, $sortDirection);

        return $query->with('company')->paginate($perPage);
    }

    /**
     * Create a new contact.
     *
     * @param array $data
     * @param bool $makePrimary Whether to make this contact the primary contact
     * @return Contact
     */
    public function createContact(array $data, bool $makePrimary = false): Contact
    {
        // If this is the first contact for the company, make it primary
        if (!isset($data['is_primary']) && $makePrimary) {
            $data['is_primary'] = true;
        }

        $contact = Contact::create($data);
        
        // If the contact should be primary, ensure it's the only primary contact
        if ($contact->is_primary) {
            $this->ensureOnlyOnePrimaryContact($contact);
        }
        
        return $contact;
    }

    /**
     * Create a new contact for a company.
     *
     * @param Company $company
     * @param array $data
     * @param bool $makePrimary Whether to make this contact the primary contact
     * @return Contact
     */
    public function createContactForCompany(Company $company, array $data, bool $makePrimary = false): Contact
    {
        $data['company_id'] = $company->id;
        
        // Check if this is the first contact for the company
        $isFirstContact = $company->contacts()->count() === 0;
        
        // If this is the first contact, or if explicitly requested, make it primary
        return $this->createContact($data, $makePrimary || $isFirstContact);
    }

    /**
     * Update an existing contact.
     *
     * @param Contact $contact
     * @param array $data
     * @return Contact
     */
    public function updateContact(Contact $contact, array $data): Contact
    {
        $wasPrimary = $contact->is_primary;
        $willBePrimary = isset($data['is_primary']) && $data['is_primary'];
        
        $contact->update($data);
        
        // If contact was set as primary, ensure it's the only primary contact
        if (!$wasPrimary && $willBePrimary) {
            $this->ensureOnlyOnePrimaryContact($contact);
        }
        
        return $contact;
    }

    /**
     * Delete a contact.
     *
     * @param Contact $contact
     * @return bool
     */
    public function deleteContact(Contact $contact): bool
    {
        // Use delete() which triggers the SoftDeletes trait
        return $contact->delete();
    }

    /**
     * Get contact by ID.
     *
     * @param int $id
     * @param bool $withTrashed Whether to include trashed contacts
     * @return Contact|null
     */
    public function getContactById(int $id, bool $withTrashed = false): ?Contact
    {
        $query = Contact::query();
        
        if ($withTrashed) {
            $query->withTrashed();
        }
        
        return $query->with('company')->find($id);
    }

    /**
     * Make sure only one contact per company is primary.
     *
     * @param Contact $primaryContact
     * @return void
     */
    protected function ensureOnlyOnePrimaryContact(Contact $primaryContact): void
    {
        if ($primaryContact->company_id) {
            Contact::where('company_id', $primaryContact->company_id)
                ->where('id', '!=', $primaryContact->id)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }
    }
} 