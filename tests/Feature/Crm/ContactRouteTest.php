<?php

namespace Tests\Feature\Crm;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactRouteTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Test the contacts index route.
     */
    public function test_contacts_index_route(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.crm.contacts.index'));
        $response->assertStatus(200);
    }

    /**
     * Test the contacts create route.
     */
    public function test_contacts_create_route(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.crm.contacts.create'));
        $response->assertStatus(200);
    }

    /**
     * Test the contacts show route with the controller functionality.
     */
    public function test_contacts_show_route_controller(): void
    {
        // Create minimal database records for the test
        $company = \App\Models\Crm\Company::create([
            'name' => 'Test Company',
            'status' => 'lead',
        ]);
        
        $contact = \App\Models\Crm\Contact::create([
            'company_id' => $company->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'is_primary' => true,
            'is_active' => true,
        ]);

        // Test the route
        $response = $this->actingAs($this->user)->get(route('admin.crm.contacts.show', $contact->id));
        $response->assertStatus(200);
        $response->assertSee($contact->first_name);
        $response->assertSee($contact->last_name);
        $response->assertSee($company->name);
    }

    /**
     * Test the contacts edit route.
     */
    public function test_contacts_edit_route(): void
    {
        // Create minimal database records for the test
        $company = \App\Models\Crm\Company::create([
            'name' => 'Test Company',
            'status' => 'lead',
        ]);
        
        $contact = \App\Models\Crm\Contact::create([
            'company_id' => $company->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'is_primary' => true,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)->get(route('admin.crm.contacts.edit', $contact->id));
        $response->assertStatus(200);
    }
} 