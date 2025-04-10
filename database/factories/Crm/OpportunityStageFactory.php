<?php

namespace Database\Factories\Crm;

use App\Models\Crm\OpportunityStage;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpportunityStageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OpportunityStage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $position = 10;
        
        return [
            'name' => $this->faker->unique()->word(),
            'key' => $this->faker->unique()->slug(1),
            'description' => $this->faker->sentence(),
            'position' => $position += 10,
            'probability' => $this->faker->numberBetween(0, 100),
            'color' => $this->faker->hexColor(),
            'is_active' => true,
            'is_won_stage' => false,
            'is_lost_stage' => false,
        ];
    }

    /**
     * Configure the stage as a won stage.
     *
     * @return $this
     */
    public function asWonStage()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Won',
                'key' => 'won',
                'probability' => 100,
                'color' => '#22C55E',
                'is_won_stage' => true,
                'is_lost_stage' => false,
            ];
        });
    }

    /**
     * Configure the stage as a lost stage.
     *
     * @return $this
     */
    public function asLostStage()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Lost',
                'key' => 'lost',
                'probability' => 0,
                'color' => '#EF4444',
                'is_won_stage' => false,
                'is_lost_stage' => true,
            ];
        });
    }
} 