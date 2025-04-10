<?php

namespace Database\Factories\Crm;

use App\Models\Crm\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'website' => $this->faker->url(),
            'industry' => $this->faker->randomElement(['Technology', 'Finance', 'Healthcare', 'Manufacturing', 'Retail']),
            'description' => $this->faker->paragraph(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'zip_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'logo' => null,
            'status' => $this->faker->randomElement([
                Company::STATUS_LEAD,
                Company::STATUS_PROSPECT,
                Company::STATUS_CUSTOMER,
                Company::STATUS_CHURNED
            ]),
            'notes' => $this->faker->paragraph(),
            'is_active' => true,
        ];
    }
} 