<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Crm;

use App\Models\Crm\Company;
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
        $this->company = Company::with('contacts')->findOrFail($this->companyId);
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