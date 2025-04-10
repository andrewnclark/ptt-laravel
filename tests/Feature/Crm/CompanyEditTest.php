<?php

namespace Tests\Feature\Crm;

use App\Livewire\Admin\Crm\EditCompanyForm;
use App\Models\Crm\Activity;
use App\Models\Crm\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CompanyEditTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Test that the company edit page loads successfully.
     */
    public function test_company_edit_page_loads_successfully(): void
    {
        $company = Company::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('admin.crm.companies.edit', $company->id));

        $response->assertStatus(200);
        $response->assertSeeLivewire('admin.crm.edit-company-form');
    }

    /**
     * Test the initial form values are set correctly from the company.
     */
    public function test_form_values_are_set_from_company(): void
    {
        $company = Company::factory()->create([
            'name' => 'Test Company',
            'email' => 'test@example.com',
            'phone' => '123-456-7890',
            'website' => 'https://example.com',
            'industry' => 'Technology',
            'status' => Company::STATUS_LEAD,
            'is_active' => true,
            'description' => 'Test description'
        ]);

        Livewire::actingAs($this->user)
            ->test(EditCompanyForm::class, ['company' => $company])
            ->assertSet('companyData.name', 'Test Company')
            ->assertSet('companyData.email', 'test@example.com')
            ->assertSet('companyData.phone', '123-456-7890')
            ->assertSet('companyData.website', 'https://example.com')
            ->assertSet('companyData.industry', 'Technology')
            ->assertSet('companyData.status', Company::STATUS_LEAD)
            ->assertSet('companyData.is_active', true)
            ->assertSet('companyData.description', 'Test description');
    }

    /**
     * Test validation rules are enforced.
     */
    public function test_validation_rules_are_enforced(): void
    {
        $company = Company::factory()->create();

        Livewire::actingAs($this->user)
            ->test(EditCompanyForm::class, ['company' => $company])
            ->set('companyData.name', '') // Required field intentionally left empty
            ->set('companyData.email', 'not-an-email') // Invalid email format
            ->set('companyData.phone', '1234') // Too short
            ->set('companyData.website', 'not-a-url') // Invalid URL
            ->call('saveCompany')
            ->assertHasErrors([
                'companyData.name' => 'required',
                'companyData.email' => 'email',
                'companyData.phone' => 'min:7',
                'companyData.website' => 'url'
            ]);
    }

    /**
     * Test successfully updating a company.
     */
    public function test_can_update_company(): void
    {
        $company = Company::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'status' => Company::STATUS_LEAD
        ]);

        $newData = [
            'name' => 'Updated Company Name',
            'email' => 'updated@example.com',
            'phone' => '987-654-3210',
            'website' => 'https://updated-example.com',
            'industry' => 'Finance',
            'description' => 'Updated description',
            'status' => Company::STATUS_PROSPECT,
            'is_active' => true,
        ];

        Livewire::actingAs($this->user)
            ->test(EditCompanyForm::class, ['company' => $company])
            ->set('companyData.name', $newData['name'])
            ->set('companyData.email', $newData['email'])
            ->set('companyData.phone', $newData['phone'])
            ->set('companyData.website', $newData['website'])
            ->set('companyData.industry', $newData['industry'])
            ->set('companyData.description', $newData['description'])
            ->set('companyData.status', $newData['status'])
            ->set('companyData.is_active', $newData['is_active'])
            ->call('saveCompany')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.crm.companies.show', $company->id));

        // Verify the company was updated in the database
        $this->assertDatabaseHas('crm_companies', [
            'id' => $company->id,
            'name' => $newData['name'],
            'email' => $newData['email'],
            'status' => $newData['status'],
        ]);
    }

    /**
     * Test that an activity is recorded when updating a company.
     */
    public function test_activity_is_recorded_for_company_update(): void
    {
        $company = Company::factory()->create([
            'name' => 'Activity Test Company',
            'status' => Company::STATUS_LEAD
        ]);

        Livewire::actingAs($this->user)
            ->test(EditCompanyForm::class, ['company' => $company])
            ->set('companyData.name', 'Updated Activity Test Company')
            ->set('companyData.status', Company::STATUS_PROSPECT)
            ->call('saveCompany');

        // Check if an activity was recorded for this update
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Company::class,
            'subject_id' => $company->id,
            'user_id' => $this->user->id,
            'type' => Activity::TYPE_UPDATED,
        ]);
    }

    /**
     * Test that status change records a specific activity.
     */
    public function test_status_change_records_specific_activity(): void
    {
        $company = Company::factory()->create([
            'status' => Company::STATUS_LEAD
        ]);

        Livewire::actingAs($this->user)
            ->test(EditCompanyForm::class, ['company' => $company])
            ->set('companyData.status', Company::STATUS_CUSTOMER)
            ->call('saveCompany');

        // Check if a status change activity was recorded
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Company::class,
            'subject_id' => $company->id,
            'type' => Activity::TYPE_STATUS_CHANGED,
        ]);
    }
}
