<?php

namespace Tests\Feature\Crm;

use App\Livewire\Admin\Crm\CompanyManager;
use App\Models\Crm\Activity;
use App\Models\Crm\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CompanyDeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Test company deletion confirmation shows up.
     */
    public function test_company_deletion_confirmation_shows_up(): void
    {
        $company = Company::factory()->create();

        Livewire::actingAs($this->user)
            ->test(CompanyManager::class)
            ->call('confirmCompanyDeletion', $company->id)
            ->assertSet('deletingCompany.id', $company->id);
    }

    /**
     * Test company soft deletion.
     */
    public function test_company_soft_deletion(): void
    {
        $company = Company::factory()->create([
            'name' => 'Test Company for Deletion'
        ]);

        $this->assertDatabaseHas('crm_companies', [
            'id' => $company->id,
            'deleted_at' => null
        ]);

        Livewire::actingAs($this->user)
            ->test(CompanyManager::class)
            ->call('confirmCompanyDeletion', $company->id)
            ->call('deleteCompany');

        // Check that the company is soft deleted (deleted_at is not null)
        $this->assertSoftDeleted('crm_companies', [
            'id' => $company->id
        ]);
    }

    /**
     * Test activity is recorded for company deletion.
     */
    public function test_activity_is_recorded_for_company_deletion(): void
    {
        $company = Company::factory()->create([
            'name' => 'Test Company for Activity Logging'
        ]);

        Livewire::actingAs($this->user)
            ->test(CompanyManager::class)
            ->call('confirmCompanyDeletion', $company->id)
            ->call('deleteCompany');

        // Check if an activity was recorded for this deletion
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => Company::class,
            'subject_id' => $company->id,
            'user_id' => $this->user->id,
            'type' => Activity::TYPE_DELETED,
        ]);
    }

    /**
     * Test canceling company deletion.
     */
    public function test_canceling_company_deletion(): void
    {
        $company = Company::factory()->create();

        Livewire::actingAs($this->user)
            ->test(CompanyManager::class)
            ->call('confirmCompanyDeletion', $company->id)
            ->assertSet('deletingCompany.id', $company->id)
            ->call('cancelDeletion')
            ->assertSet('deletingCompany', null);

        // Verify company still exists and is not deleted
        $this->assertDatabaseHas('crm_companies', [
            'id' => $company->id,
            'deleted_at' => null
        ]);
    }
}
