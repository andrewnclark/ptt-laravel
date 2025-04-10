<?php

namespace Tests\Feature\Crm;

use App\Livewire\Admin\Crm\CreateCompanyForm;
use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CompanyCreateWithContactTest extends TestCase
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
     * Test that the company create form has contact fields.
     */
    public function test_company_create_form_has_contact_fields(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.crm.companies.create'));

        $response->assertStatus(200);
        $response->assertSee('Primary Contact Information');
        $response->assertSee('First Name');
        $response->assertSee('Last Name');
        $response->assertSee('Email');
        $response->assertSee('Phone');
    }

    /**
     * Test validation rules for contact are enforced.
     */
    public function test_validation_rules_for_contact_are_enforced(): void
    {
        Livewire::actingAs($this->user)
            ->test(CreateCompanyForm::class)
            ->set('company', [
                'name' => 'Test Company',
                'status' => 'lead',
                'is_active' => true,
            ])
            ->set('contact', [
                'first_name' => '', // Required field intentionally left empty
                'email' => 'not-an-email', // Invalid email format
                'phone' => '123', // Too short
            ])
            ->call('saveCompany')
            ->assertHasErrors([
                'contact.first_name' => 'required',
                'contact.email' => 'email',
                'contact.phone' => 'min',
            ]);
    }

    /**
     * Test successfully creating a company with contact.
     */
    public function test_can_create_company_with_contact(): void
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
        
        $contactData = [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'job_title' => $this->faker->jobTitle(),
            'is_primary' => true,
            'is_active' => true,
        ];

        Livewire::actingAs($this->user)
            ->test(CreateCompanyForm::class)
            ->set('company', $companyData)
            ->set('contact', $contactData)
            ->call('saveCompany')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.crm.companies.index'));

        // Verify the company was created in the database
        $this->assertDatabaseHas('crm_companies', [
            'name' => $companyData['name'],
            'email' => $companyData['email'],
            'status' => $companyData['status'],
        ]);
        
        // Get the created company
        $company = Company::where('name', $companyData['name'])->first();
        
        // Verify the contact was created and associated with the company
        $this->assertDatabaseHas('crm_contacts', [
            'company_id' => $company->id,
            'first_name' => $contactData['first_name'],
            'last_name' => $contactData['last_name'],
            'email' => $contactData['email'],
            'is_primary' => true,
        ]);
        
        // Verify the company has exactly one contact
        $this->assertEquals(1, $company->contacts()->count());
        
        // Verify the contact is marked as primary
        $this->assertTrue($company->contacts()->first()->is_primary);
    }

    /**
     * Test the reset form functionality resets contact fields too.
     */
    public function test_form_reset_clears_contact_data(): void
    {
        $companyData = [
            'name' => $this->faker->company(),
        ];
        
        $contactData = [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
        ];

        Livewire::actingAs($this->user)
            ->test(CreateCompanyForm::class)
            ->set('company', $companyData)
            ->set('contact', $contactData)
            ->call('resetForm')
            ->assertSet('company.name', '')
            ->assertSet('contact.first_name', '')
            ->assertSet('contact.last_name', '');
    }
} 