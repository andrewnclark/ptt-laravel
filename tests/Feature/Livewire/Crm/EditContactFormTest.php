<?php

namespace Tests\Feature\Livewire\Crm;

use App\Livewire\Admin\Crm\EditContactForm;
use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class EditContactFormTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @var User */
    protected $user;

    /** @var Contact */
    protected $contact;

    /** @var Company */
    protected $company;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user
        $this->user = User::factory()->create();
        
        // Create a company
        $this->company = Company::create([
            'name' => 'Test Company',
            'status' => 'lead',
        ]);
        
        // Create a contact
        $this->contact = Contact::create([
            'company_id' => $this->company->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '123-456-7890',
            'job_title' => 'Manager',
            'is_primary' => true,
            'is_active' => true,
        ]);
        
        // Act as the user
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_mounts_with_contact_data()
    {
        // Test the component mounts with contact data
        Livewire::test(EditContactForm::class, ['contact' => $this->contact])
            ->assertSet('contact.first_name', 'John')
            ->assertSet('contact.last_name', 'Doe')
            ->assertSet('contact.email', 'john.doe@example.com')
            ->assertSet('contact.phone', '123-456-7890')
            ->assertSet('contact.job_title', 'Manager')
            ->assertSet('contact.is_primary', true)
            ->assertSet('contact.is_active', true)
            ->assertSet('contact.company_id', $this->company->id);
    }

    /** @test */
    public function it_updates_contact_successfully()
    {
        // Test updating the contact
        Livewire::test(EditContactForm::class, ['contact' => $this->contact])
            ->set('contact.first_name', 'Jane')
            ->set('contact.last_name', 'Smith')
            ->set('contact.email', 'jane.smith@example.com')
            ->set('contact.phone', '987-654-3210')
            ->set('contact.job_title', 'Director')
            ->set('contact.is_primary', false)
            ->set('contact.is_active', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.crm.contacts.show', $this->contact->id));

        // Assert the database was updated
        $this->assertDatabaseHas('crm_contacts', [
            'id' => $this->contact->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '987-654-3210',
            'job_title' => 'Director',
            'is_primary' => false,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        // Test validation rules
        Livewire::test(EditContactForm::class, ['contact' => $this->contact])
            ->set('contact.first_name', '')
            ->set('contact.last_name', '')
            ->set('contact.email', '')
            ->call('save')
            ->assertHasErrors(['contact.first_name', 'contact.last_name', 'contact.email']);
    }

    /** @test */
    public function it_validates_email_format()
    {
        // Test email validation
        Livewire::test(EditContactForm::class, ['contact' => $this->contact])
            ->set('contact.email', 'invalid-email')
            ->call('save')
            ->assertHasErrors(['contact.email']);
    }

    /** @test */
    public function it_does_not_update_when_nothing_changed()
    {
        // Test no update when no changes
        Livewire::test(EditContactForm::class, ['contact' => $this->contact])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.crm.contacts.show', $this->contact->id));

        // Assert the database contains original values
        $this->assertDatabaseHas('crm_contacts', [
            'id' => $this->contact->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ]);
    }

    /** @test */
    public function it_can_change_company()
    {
        // Create another company
        $newCompany = Company::create([
            'name' => 'New Company',
            'status' => 'customer',
        ]);

        // Test changing the company
        Livewire::test(EditContactForm::class, ['contact' => $this->contact])
            ->set('contact.company_id', $newCompany->id)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.crm.contacts.show', $this->contact->id));

        // Assert the database was updated
        $this->assertDatabaseHas('crm_contacts', [
            'id' => $this->contact->id,
            'company_id' => $newCompany->id,
        ]);
    }

    /** @test */
    public function it_dispatches_event_on_successful_update()
    {
        // Test event dispatching after update
        Livewire::test(EditContactForm::class, ['contact' => $this->contact])
            ->set('contact.job_title', 'Updated Title')
            ->call('save')
            ->assertDispatched('contact-updated');
    }
} 