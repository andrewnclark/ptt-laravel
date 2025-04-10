<?php

namespace Tests\Feature\Crm;

use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use App\Models\Crm\Opportunity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactControllerTest extends TestCase
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
     * Test the contact show page displays the correct contact information.
     */
    public function test_show_page_displays_contact_information(): void
    {
        // Create a company
        $company = Company::create([
            'name' => 'Test Company',
            'email' => 'company@example.com',
            'status' => 'lead',
            'is_active' => true,
        ]);
        
        // Create a contact
        $contact = Contact::create([
            'company_id' => $company->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '555-123-4567',
            'job_title' => 'Sales Manager',
            'is_primary' => true,
            'is_active' => true,
        ]);
        
        // Create an opportunity for the contact
        $opportunity = Opportunity::create([
            'company_id' => $company->id,
            'contact_id' => $contact->id,
            'title' => 'Test Opportunity',
            'value' => 5000.00,
            'status' => 'negotiation',
            'expected_close_date' => now()->addMonths(1),
        ]);
        
        // Visit the contact show page
        $response = $this->actingAs($this->user)
            ->get(route('admin.crm.contacts.show', $contact->id));
        
        // Assert the response is successful
        $response->assertStatus(200);
        
        // Assert the view has the contact
        $response->assertViewHas('contact', function ($viewContact) use ($contact) {
            return $viewContact->id === $contact->id;
        });
        
        // Assert the view has the opportunities
        $response->assertViewHas('opportunities', function ($viewOpportunities) use ($opportunity) {
            return $viewOpportunities->contains($opportunity);
        });
        
        // Assert the page contains the contact's name
        $response->assertSee($contact->first_name);
        $response->assertSee($contact->last_name);
        
        // Assert the page contains the contact's email
        $response->assertSee($contact->email);
        
        // Assert the page contains the contact's phone
        $response->assertSee($contact->phone);
        
        // Assert the page contains the contact's job title
        $response->assertSee($contact->job_title);
        
        // Assert the page contains the company name
        $response->assertSee($company->name);
        
        // Assert the page contains the opportunity information
        $response->assertSee($opportunity->title);
        $response->assertSee('$5,000.00'); // The formatted amount
    }
} 