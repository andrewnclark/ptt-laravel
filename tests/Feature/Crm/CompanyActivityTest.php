<?php

namespace Tests\Feature\Crm;

use App\Livewire\Admin\Crm\EditCompanyForm;
use App\Models\Crm\Activity;
use App\Models\Crm\Company;
use App\Models\User;
use App\Services\Crm\CompanyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CompanyActivityTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected User $user;
    protected Company $company;
    protected CompanyService $companyService;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->company = Company::factory()->create([
            'name' => 'Test Company',
            'status' => Company::STATUS_LEAD
        ]);
        $this->companyService = app(CompanyService::class);
    }

    /**
     * Test that creating a company records a creation activity.
     */
    public function test_company_creation_records_activity(): void
    {
        $companyData = [
            'name' => 'New Test Company',
            'email' => 'test@example.com',
            'status' => Company::STATUS_PROSPECT,
            'is_active' => true,
        ];

        $this->actingAs($this->user);
        $company = $this->companyService->createCompany($companyData);

        // Verify activity record was created
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Company::class,
            'subject_id' => $company->id,
            'type' => Activity::TYPE_CREATED,
            'user_id' => $this->user->id,
        ]);

        // Verify activity contains the company attributes in properties
        $activity = Activity::where('subject_id', $company->id)
            ->where('type', Activity::TYPE_CREATED)
            ->first();

        $this->assertNotNull($activity);
        $this->assertArrayHasKey('attributes', $activity->properties);
        $this->assertEquals($companyData['name'], $activity->properties['attributes']['name']);
    }

    /**
     * Test that updating a company through service records an updated activity.
     */
    public function test_company_update_through_service_records_activity(): void
    {
        $this->actingAs($this->user);

        $updateData = [
            'name' => 'Updated Company Name',
            'description' => 'This is an updated description',
        ];

        $this->companyService->updateCompany($this->company, $updateData);

        // Verify activity record was created
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Company::class,
            'subject_id' => $this->company->id,
            'type' => Activity::TYPE_UPDATED,
            'user_id' => $this->user->id,
        ]);

        // Verify activity contains the changes
        $activity = Activity::where('subject_id', $this->company->id)
            ->where('type', Activity::TYPE_UPDATED)
            ->first();

        $this->assertNotNull($activity);
        $this->assertArrayHasKey('new', $activity->properties);
        $this->assertArrayHasKey('old', $activity->properties);
        $this->assertEquals($updateData['name'], $activity->properties['new']['name']);
    }

    /**
     * Test that changing status records a specific status changed activity.
     */
    public function test_status_change_records_specific_activity(): void
    {
        $this->actingAs($this->user);
        
        $newStatus = Company::STATUS_CUSTOMER;
        $this->company->changeStatus($newStatus);

        // Verify status change activity was recorded
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Company::class,
            'subject_id' => $this->company->id,
            'type' => Activity::TYPE_STATUS_CHANGED,
            'user_id' => $this->user->id,
        ]);

        // Verify activity contains the old and new status
        $activity = Activity::where('subject_id', $this->company->id)
            ->where('type', Activity::TYPE_STATUS_CHANGED)
            ->first();

        $this->assertNotNull($activity);
        $this->assertArrayHasKey('old_status', $activity->properties);
        $this->assertArrayHasKey('new_status', $activity->properties);
        $this->assertEquals(Company::STATUS_LEAD, $activity->properties['old_status']);
        $this->assertEquals($newStatus, $activity->properties['new_status']);
    }

    /**
     * Test that company update through Livewire component records activity.
     */
    public function test_company_update_through_livewire_records_activity(): void
    {
        $this->actingAs($this->user);

        Livewire::actingAs($this->user)
            ->test(EditCompanyForm::class, ['company' => $this->company])
            ->set('companyData.name', 'Livewire Updated Name')
            ->set('companyData.industry', 'Software')
            ->call('saveCompany');

        // Verify activity record was created
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Company::class,
            'subject_id' => $this->company->id,
            'type' => Activity::TYPE_UPDATED,
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * Test that status change through Livewire component records specific activity.
     */
    public function test_status_change_through_livewire_records_specific_activity(): void
    {
        $this->actingAs($this->user);

        Livewire::actingAs($this->user)
            ->test(EditCompanyForm::class, ['company' => $this->company])
            ->set('companyData.status', Company::STATUS_PROSPECT)
            ->call('saveCompany');

        // Verify status change activity was recorded
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Company::class,
            'subject_id' => $this->company->id,
            'type' => Activity::TYPE_STATUS_CHANGED,
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * Test that simultaneous updates and status changes record both activities.
     */
    public function test_simultaneous_update_and_status_change_records_both_activities(): void
    {
        $this->actingAs($this->user);

        Livewire::actingAs($this->user)
            ->test(EditCompanyForm::class, ['company' => $this->company])
            ->set('companyData.name', 'Both Updated Name')
            ->set('companyData.status', Company::STATUS_PROSPECT)
            ->call('saveCompany');

        // Verify both regular update and status change activities were recorded
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Company::class,
            'subject_id' => $this->company->id,
            'type' => Activity::TYPE_UPDATED,
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Company::class,
            'subject_id' => $this->company->id,
            'type' => Activity::TYPE_STATUS_CHANGED,
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * Test that company delete records a deletion activity.
     */
    public function test_company_delete_records_activity(): void
    {
        $this->actingAs($this->user);
        
        $this->companyService->deleteCompany($this->company);

        // Verify deletion activity was recorded
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Company::class,
            'subject_id' => $this->company->id,
            'type' => Activity::TYPE_DELETED,
            'user_id' => $this->user->id,
        ]);
    }
} 