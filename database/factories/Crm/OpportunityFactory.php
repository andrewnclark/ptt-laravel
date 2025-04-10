<?php

namespace Database\Factories\Crm;

use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use App\Models\Crm\Opportunity;
use App\Models\Crm\OpportunityStage;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpportunityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Opportunity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Create a random stage ID if none exists in the database
        $stageId = OpportunityStage::query()->exists() 
            ? OpportunityStage::inRandomOrder()->first()->id 
            : 1;

        return [
            'company_id' => Company::factory(),
            'contact_id' => Contact::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'value' => $this->faker->randomFloat(2, 1000, 100000),
            'status' => $this->faker->randomElement([
                Opportunity::STATUS_NEW,
                Opportunity::STATUS_QUALIFIED,
                Opportunity::STATUS_PROPOSAL,
                Opportunity::STATUS_WON,
                Opportunity::STATUS_LOST,
            ]),
            'stage_id' => $stageId,
            'source' => $this->faker->randomElement([
                Opportunity::SOURCE_WEBSITE,
                Opportunity::SOURCE_REFERRAL,
                Opportunity::SOURCE_COLD_CALL,
                Opportunity::SOURCE_EVENT,
                Opportunity::SOURCE_OTHER,
            ]),
            'expected_close_date' => $this->faker->dateTimeBetween('now', '+3 months'),
            'actual_close_date' => $this->faker->optional(0.3)->dateTimeBetween('-1 month', 'now'),
            'probability' => $this->faker->numberBetween(10, 100),
            'notes' => $this->faker->optional(0.7)->paragraph(),
        ];
    }
} 