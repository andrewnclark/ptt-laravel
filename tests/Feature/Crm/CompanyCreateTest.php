<?php

namespace Tests\Feature\Crm;

use App\Livewire\Admin\Crm\CreateCompanyForm;
use App\Models\Crm\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CompanyCreateTest extends TestCase
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
     * Test that the company create page loads successfully.
     */
    public function test_company_create_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.crm.companies.create'));

        $response->assertStatus(200);
        $response->assertSeeLivewire('admin.crm.create-company-form');
    }

    /**
     * Test validation rules are enforced.
     */
    public function test_validation_rules_are_enforced(): void
    {
        Livewire::actingAs($this->user)
            ->test(CreateCompanyForm::class)
            ->set('company', [
                'name' => '', // Required field intentionally left empty
                'email' => 'not-an-email', // Invalid email format
                'phone' => '1234', // Too short
                'is_active' => 'not-a-boolean',
            ])
            ->call('saveCompany')
            ->assertHasErrors([
                'company.name' => 'required',
                'company.email' => 'email',
                'company.phone' => 'min:7',
                'company.is_active' => 'boolean',
            ]);
    }

    /**
     * Test successfully creating a company.
     */
    public function test_can_create_company(): void
    {
        $companyData = [
            'name' => $this->faker->company(),
            'email' => $this->faker->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'website' => $this->faker->url(),
            'industry' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'status' => 'lead',
            'is_active' => true,
        ];

        Livewire::actingAs($this->user)
            ->test(CreateCompanyForm::class)
            ->set('company', $companyData)
            ->call('saveCompany')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.crm.companies.index'));

        // Verify the company was created in the database
        $this->assertDatabaseHas('crm_companies', [
            'name' => $companyData['name'],
            'email' => $companyData['email'],
            'status' => $companyData['status'],
        ]);
    }

    /**
     * Test the reset form functionality.
     */
    public function test_form_can_be_reset(): void
    {
        $companyData = [
            'name' => $this->faker->company(),
            'email' => $this->faker->companyEmail(),
        ];

        Livewire::actingAs($this->user)
            ->test(CreateCompanyForm::class)
            ->set('company', $companyData)
            ->call('resetForm')
            ->assertSet('company.name', '')
            ->assertSet('company.email', '');
    }
}
