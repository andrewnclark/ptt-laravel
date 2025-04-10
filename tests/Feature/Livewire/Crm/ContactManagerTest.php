<?php

namespace Tests\Feature\Livewire\Crm;

use App\Livewire\Admin\Crm\ContactManager;
use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ContactManagerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @var User */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_displays_contacts_from_database()
    {
        // Create test data
        $company = Company::create([
            'name' => 'Test Company',
            'status' => 'lead',
        ]);

        $contact1 = Contact::create([
            'company_id' => $company->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '123-456-7890',
            'job_title' => 'Manager',
            'is_primary' => true,
            'is_active' => true,
        ]);

        $contact2 = Contact::create([
            'company_id' => $company->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '987-654-3210',
            'job_title' => 'Developer',
            'is_primary' => false,
            'is_active' => true,
        ]);

        // Test the component
        Livewire::test(ContactManager::class)
            ->assertSee('John Doe')
            ->assertSee('jane.smith@example.com')
            ->assertSee('Test Company');
    }

    /** @test */
    public function it_can_search_contacts()
    {
        // Create test data
        $company = Company::create([
            'name' => 'Test Company',
            'status' => 'lead',
        ]);

        Contact::create([
            'company_id' => $company->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '123-456-7890',
            'job_title' => 'Manager',
            'is_primary' => true,
            'is_active' => true,
        ]);

        Contact::create([
            'company_id' => $company->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '987-654-3210',
            'job_title' => 'Developer',
            'is_primary' => false,
            'is_active' => true,
        ]);

        // Test search functionality
        Livewire::test(ContactManager::class)
            ->set('search', 'john')
            ->assertSee('John Doe')
            ->assertDontSee('Jane Smith');
    }

    /** @test */
    public function it_can_filter_by_company()
    {
        // Create test data
        $company1 = Company::create([
            'name' => 'Company A',
            'status' => 'lead',
        ]);

        $company2 = Company::create([
            'name' => 'Company B',
            'status' => 'customer',
        ]);

        Contact::create([
            'company_id' => $company1->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'is_active' => true,
        ]);

        Contact::create([
            'company_id' => $company2->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'is_active' => true,
        ]);

        // Test company filter
        Livewire::test(ContactManager::class)
            ->set('companyId', $company1->id)
            ->assertSee('John Doe')
            ->assertDontSee('Jane Smith');
    }

    /** @test */
    public function it_can_filter_by_active_status()
    {
        // Create test data
        $company = Company::create([
            'name' => 'Test Company',
            'status' => 'lead',
        ]);

        Contact::create([
            'company_id' => $company->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'is_active' => true,
        ]);

        Contact::create([
            'company_id' => $company->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'is_active' => false,
        ]);

        // Test active status filter
        Livewire::test(ContactManager::class)
            ->set('isActive', true)
            ->assertSee('John Doe')
            ->assertDontSee('Jane Smith');
    }

    /** @test */
    public function it_confirms_before_deleting_a_contact()
    {
        // Create test data
        $company = Company::create([
            'name' => 'Test Company',
            'status' => 'lead',
        ]);

        $contact = Contact::create([
            'company_id' => $company->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'is_active' => true,
        ]);

        // Test delete confirmation
        Livewire::test(ContactManager::class)
            ->call('confirmDelete', $contact->id)
            ->assertSet('deletingContactId', $contact->id)
            ->assertDispatchedBrowserEvent('open-modal', ['id' => 'confirm-contact-deletion']);
    }

    /** @test */
    public function it_can_delete_a_contact()
    {
        // Create test data
        $company = Company::create([
            'name' => 'Test Company',
            'status' => 'lead',
        ]);

        $contact = Contact::create([
            'company_id' => $company->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'is_active' => true,
        ]);

        $this->assertEquals(1, Contact::count());

        // Test delete functionality
        Livewire::test(ContactManager::class)
            ->set('deletingContactId', $contact->id)
            ->call('deleteContact')
            ->assertDispatchedBrowserEvent('close-modal', ['id' => 'confirm-contact-deletion']);

        $this->assertEquals(0, Contact::count());
    }
} 