<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Crm;

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
    
    // Form fields
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $company_id;
    public $job_title;
    public $is_primary = false;
    public $is_active = true;
    
    /**
     * List of companies for the dropdown.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $companies = [];

    /**
     * Define validation rules.
     *
     * @var array
     */
    protected function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('crm_contacts', 'email')->ignore($this->contact->id)
            ],
            'phone' => 'nullable|string|max:20',
            'company_id' => 'required|exists:crm_companies,id',
            'job_title' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
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
        
        $this->companies = Company::orderBy('name')->get();
    }

    /**
     * Real-time validation of form inputs.
     *
     * @param string $propertyName
     * @return void
     */
    public function updated(string $propertyName): void
    {
        $this->validateOnly($propertyName);
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
            'is_primary' => $this->is_primary,
            'is_active' => $this->is_active,
        ]);
        
        // Handle primary contact logic if needed
        if ($this->is_primary) {
            $this->contact->setAsPrimary();
        }
        
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