<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Crm;

use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditContactForm extends Component
{
    /**
     * The contact model.
     *
     * @var Contact
     */
    public Contact $contact;

    /**
     * List of companies for the dropdown.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $companies;

    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $company_id;
    public $job_title;
    public $is_primary = false;
    public $is_active = true;

    /**
     * Define validation rules.
     *
     * @return array
     */
    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'company_id' => 'required|exists:crm_companies,id',
        'job_title' => 'nullable|string|max:255',
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Validation messages.
     *
     * @return array
     */
    protected function messages(): array
    {
        return [
            'contact.first_name.required' => 'First name is required',
            'contact.last_name.required' => 'Last name is required',
            'contact.email.required' => 'Email address is required',
            'contact.email.email' => 'Please enter a valid email address',
            'contact.email.unique' => 'This email is already in use by another contact',
            'contact.company_id.required' => 'Please select a company',
            'contact.company_id.exists' => 'The selected company is invalid',
        ];
    }

    /**
     * Mount the component.
     *
     * @param Contact $contact
     * @return void
     */
    public function mount(Contact $contact): void
    {
        $this->contact = $contact;
        $this->first_name = $contact->first_name;
        $this->last_name = $contact->last_name;
        $this->email = $contact->email;
        $this->phone = $contact->phone;
        $this->company_id = $contact->company_id;
        $this->job_title = $contact->job_title;
        $this->is_primary = $contact->is_primary;
        $this->is_active = $contact->is_active;
        $this->companies = Company::where('is_active', true)->orderBy('name')->get();
        
        // Update validation rules for email to exclude the current contact
        $this->rules['email'] = [
            'required',
            'email',
            'max:255',
            Rule::unique('crm_contacts', 'email')->ignore($contact->id)
        ];
    }

    /**
     * Save the contact data.
     *
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function save()
    {
        $validatedData = $this->validate($this->rules());
        
        $this->contact->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company_id' => $this->company_id,
            'job_title' => $this->job_title,
            'is_active' => $this->is_active,
            // Don't update is_primary here, we'll handle it separately
        ]);
        
        // Handle primary contact status with the dedicated method
        if ($this->is_primary && !$this->contact->is_primary) {
            $this->contact->setAsPrimary();
        } elseif (!$this->is_primary && $this->contact->is_primary) {
            // If removing primary status
            $this->contact->update(['is_primary' => false]);
        }
        
        // Dispatch event for any additional functionality
        $this->dispatch('contact-updated');
        
        session()->flash('success', 'Contact updated successfully.');
        
        return redirect()->route('admin.crm.contacts.show', $this->contact->id);
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.admin.crm.edit-contact-form');
    }
} 