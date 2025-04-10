<?php

namespace Tests\Unit\Services\Crm;

use App\Models\Crm\Activity;
use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use App\Models\Crm\Opportunity;
use App\Models\Crm\OpportunityStage;
use App\Models\User;
use App\Services\Crm\OpportunityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpportunityServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OpportunityService $opportunityService;
    protected User $user;
    protected Company $company;
    protected OpportunityStage $newStage;
    protected OpportunityStage $wonStage;
    protected OpportunityStage $lostStage;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->opportunityService = new OpportunityService();
        $this->user = User::factory()->create();
        $this->company = Company::factory()->create([
            'name' => 'Test Company',
            'status' => Company::STATUS_CUSTOMER,
        ]);
        
        // Create opportunity stages
        $this->newStage = OpportunityStage::create([
            'name' => 'New Lead',
            'key' => 'new',
            'description' => 'Initial contact',
            'position' => 10,
            'probability' => 10.00,
            'color' => '#3B82F6',
            'is_active' => true,
            'is_won_stage' => false,
            'is_lost_stage' => false,
        ]);
        
        $this->wonStage = OpportunityStage::create([
            'name' => 'Won',
            'key' => 'won',
            'description' => 'Deal won',
            'position' => 50,
            'probability' => 100.00,
            'color' => '#22C55E',
            'is_active' => true,
            'is_won_stage' => true,
            'is_lost_stage' => false,
        ]);
        
        $this->lostStage = OpportunityStage::create([
            'name' => 'Lost',
            'key' => 'lost',
            'description' => 'Deal lost',
            'position' => 60,
            'probability' => 0.00,
            'color' => '#EF4444',
            'is_active' => true,
            'is_won_stage' => false,
            'is_lost_stage' => true,
        ]);
        
        // Set the authenticated user for testing
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_create_an_opportunity()
    {
        // Arrange
        $data = [
            'company_id' => $this->company->id,
            'title' => 'New Business Opportunity',
            'description' => 'Potential new client deal',
            'value' => 10000.00,
            'status' => Opportunity::STATUS_NEW,
            'stage_id' => $this->newStage->id,
            'source' => Opportunity::SOURCE_WEBSITE,
            'expected_close_date' => now()->addMonths(2)->format('Y-m-d'),
            'probability' => 20,
        ];

        // Act
        $opportunity = $this->opportunityService->createOpportunity($data);

        // Assert
        $this->assertDatabaseHas('crm_opportunities', [
            'id' => $opportunity->id,
            'company_id' => $this->company->id,
            'title' => 'New Business Opportunity',
            'status' => Opportunity::STATUS_NEW,
            'stage_id' => $this->newStage->id,
        ]);
        
        $this->assertEquals('Potential new client deal', $opportunity->description);
        $this->assertEquals(10000.00, $opportunity->value);
        $this->assertEquals(Opportunity::SOURCE_WEBSITE, $opportunity->source);
    }

    /** @test */
    public function it_sets_stage_id_based_on_status_if_not_provided()
    {
        // Arrange
        $data = [
            'company_id' => $this->company->id,
            'title' => 'New Business Opportunity',
            'status' => Opportunity::STATUS_NEW,
            'value' => 5000.00,
        ];

        // Act
        $opportunity = $this->opportunityService->createOpportunity($data);

        // Assert
        $this->assertEquals($this->newStage->id, $opportunity->stage_id);
    }

    /** @test */
    public function it_can_update_an_opportunity()
    {
        // Arrange
        $opportunity = Opportunity::factory()->create([
            'company_id' => $this->company->id,
            'title' => 'Original Title',
            'value' => 5000.00,
            'stage_id' => $this->newStage->id,
        ]);
        
        $updateData = [
            'title' => 'Updated Title',
            'value' => 7500.00,
            'description' => 'Updated description',
        ];

        // Act
        $updated = $this->opportunityService->updateOpportunity($opportunity, $updateData);

        // Assert
        $this->assertTrue($updated);
        $this->assertDatabaseHas('crm_opportunities', [
            'id' => $opportunity->id,
            'title' => 'Updated Title',
            'value' => 7500.00,
            'description' => 'Updated description',
        ]);
    }

    /** @test */
    public function it_can_move_an_opportunity_to_a_new_stage()
    {
        // Arrange
        $opportunity = Opportunity::factory()->create([
            'company_id' => $this->company->id,
            'title' => 'Test Opportunity',
            'stage_id' => $this->newStage->id,
            'status' => Opportunity::STATUS_NEW,
            'probability' => 10,
        ]);

        // Act
        $moved = $this->opportunityService->moveToStage($opportunity, $this->wonStage->id);
        $opportunity->refresh();

        // Assert
        $this->assertTrue($moved);
        $this->assertEquals($this->wonStage->id, $opportunity->stage_id);
        $this->assertEquals(Opportunity::STATUS_WON, $opportunity->status);
        $this->assertEquals(100, $opportunity->probability);
        $this->assertNotNull($opportunity->actual_close_date);
        
        // Verify activity was recorded
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => get_class($opportunity),
            'subject_id' => $opportunity->id,
            'type' => Activity::TYPE_STAGE_CHANGED,
        ]);
    }

    /** @test */
    public function it_can_mark_an_opportunity_as_won()
    {
        // Arrange
        $opportunity = Opportunity::factory()->create([
            'company_id' => $this->company->id,
            'title' => 'Test Opportunity',
            'stage_id' => $this->newStage->id,
            'status' => Opportunity::STATUS_NEW,
            'value' => 5000.00,
        ]);

        // Act
        $result = $this->opportunityService->markAsWon($opportunity, [
            'value' => 7500.00 // Final value higher than original estimate
        ]);
        $opportunity->refresh();

        // Assert
        $this->assertTrue($result);
        $this->assertEquals($this->wonStage->id, $opportunity->stage_id);
        $this->assertEquals(Opportunity::STATUS_WON, $opportunity->status);
        $this->assertEquals(7500.00, $opportunity->value);
        $this->assertNotNull($opportunity->actual_close_date);
    }

    /** @test */
    public function it_can_mark_an_opportunity_as_lost_with_reason()
    {
        // Arrange
        $opportunity = Opportunity::factory()->create([
            'company_id' => $this->company->id,
            'title' => 'Test Opportunity',
            'stage_id' => $this->newStage->id,
            'status' => Opportunity::STATUS_NEW,
        ]);

        // Act
        $result = $this->opportunityService->markAsLost($opportunity, [
            'reason' => 'Price too high for client'
        ]);
        $opportunity->refresh();

        // Assert
        $this->assertTrue($result);
        $this->assertEquals($this->lostStage->id, $opportunity->stage_id);
        $this->assertEquals(Opportunity::STATUS_LOST, $opportunity->status);
        $this->assertNotNull($opportunity->actual_close_date);
        
        // Verify reason was recorded in activity
        $this->assertDatabaseHas('crm_activities', [
            'subject_type' => get_class($opportunity),
            'subject_id' => $opportunity->id,
            'type' => Activity::TYPE_CUSTOM,
            'description' => 'Lost reason: Price too high for client',
        ]);
    }

    /** @test */
    public function it_can_get_opportunities_with_filters()
    {
        // Arrange
        $company2 = Company::factory()->create(['name' => 'Second Company']);
        
        // Create several opportunities with different attributes
        Opportunity::factory()->create([
            'company_id' => $this->company->id,
            'title' => 'First Opportunity',
            'status' => Opportunity::STATUS_NEW,
            'stage_id' => $this->newStage->id,
            'value' => 5000.00,
        ]);
        
        Opportunity::factory()->create([
            'company_id' => $this->company->id,
            'title' => 'Second Opportunity',
            'status' => Opportunity::STATUS_WON,
            'stage_id' => $this->wonStage->id,
            'value' => 10000.00,
        ]);
        
        Opportunity::factory()->create([
            'company_id' => $company2->id,
            'title' => 'Third Opportunity',
            'status' => Opportunity::STATUS_NEW,
            'stage_id' => $this->newStage->id,
            'value' => 7500.00,
        ]);

        // Act - Filter by company
        $companyOpportunities = $this->opportunityService->getOpportunities([
            'company_id' => $this->company->id
        ]);
        
        // Act - Filter by status
        $newOpportunities = $this->opportunityService->getOpportunities([
            'status' => Opportunity::STATUS_NEW
        ]);
        
        // Act - Filter by stage
        $wonStageOpportunities = $this->opportunityService->getOpportunities([
            'stage_id' => $this->wonStage->id
        ]);
        
        // Act - Filter by value range
        $highValueOpportunities = $this->opportunityService->getOpportunities([
            'value_min' => 7500.00
        ]);

        // Assert
        $this->assertEquals(2, $companyOpportunities->total());
        $this->assertEquals(2, $newOpportunities->total());
        $this->assertEquals(1, $wonStageOpportunities->total());
        $this->assertEquals(2, $highValueOpportunities->total());
    }

    /** @test */
    public function it_can_get_pipeline_opportunities_grouped_by_stage()
    {
        // Arrange
        $company2 = Company::factory()->create(['name' => 'Second Company']);
        
        Opportunity::factory()->create([
            'company_id' => $this->company->id,
            'title' => 'New Opportunity 1',
            'stage_id' => $this->newStage->id,
            'value' => 5000.00,
        ]);
        
        Opportunity::factory()->create([
            'company_id' => $this->company->id,
            'title' => 'New Opportunity 2',
            'stage_id' => $this->newStage->id,
            'value' => 7500.00,
        ]);
        
        Opportunity::factory()->create([
            'company_id' => $company2->id,
            'title' => 'Won Opportunity',
            'stage_id' => $this->wonStage->id,
            'value' => 10000.00,
        ]);

        // Act
        $pipelineData = $this->opportunityService->getPipelineOpportunities();

        // Assert
        $this->assertArrayHasKey($this->newStage->id, $pipelineData);
        $this->assertArrayHasKey($this->wonStage->id, $pipelineData);
        $this->assertArrayHasKey($this->lostStage->id, $pipelineData);
        
        $this->assertEquals(2, $pipelineData[$this->newStage->id]['count']);
        $this->assertEquals(12500.00, $pipelineData[$this->newStage->id]['total_value']);
        
        $this->assertEquals(1, $pipelineData[$this->wonStage->id]['count']);
        $this->assertEquals(10000.00, $pipelineData[$this->wonStage->id]['total_value']);
        
        $this->assertEquals(0, $pipelineData[$this->lostStage->id]['count']);
    }

    /** @test */
    public function it_can_get_pipeline_summary_statistics()
    {
        // Arrange
        Opportunity::factory()->create([
            'company_id' => $this->company->id,
            'title' => 'New Opportunity 1',
            'stage_id' => $this->newStage->id,
            'status' => Opportunity::STATUS_NEW,
            'value' => 5000.00,
        ]);
        
        Opportunity::factory()->create([
            'company_id' => $this->company->id,
            'title' => 'New Opportunity 2',
            'stage_id' => $this->newStage->id,
            'status' => Opportunity::STATUS_NEW,
            'value' => 7500.00,
        ]);
        
        Opportunity::factory()->create([
            'company_id' => $this->company->id,
            'title' => 'Won Opportunity',
            'stage_id' => $this->wonStage->id,
            'status' => Opportunity::STATUS_WON,
            'value' => 10000.00,
        ]);
        
        Opportunity::factory()->create([
            'company_id' => $this->company->id,
            'title' => 'Lost Opportunity',
            'stage_id' => $this->lostStage->id,
            'status' => Opportunity::STATUS_LOST,
            'value' => 15000.00,
        ]);

        // Act
        $summary = $this->opportunityService->getPipelineSummary();

        // Assert
        $this->assertEquals(12500.00, $summary['total_value']);
        $this->assertEquals(2, $summary['total_count']);
        $this->assertEquals(10000.00, $summary['won_value']);
        $this->assertEquals(1, $summary['won_count']);
        $this->assertEquals(15000.00, $summary['lost_value']);
        $this->assertEquals(1, $summary['lost_count']);
    }
} 