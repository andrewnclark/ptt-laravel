<?php

namespace Database\Factories\Crm;

use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'mobile' => $this->faker->phoneNumber(),
            'job_title' => $this->faker->jobTitle(),
            'department' => $this->faker->randomElement(['Sales', 'Marketing', 'Engineering', 'Finance', 'HR']),
            'avatar' => null,
            'notes' => $this->faker->optional(0.6)->paragraph(),
            'is_primary' => $this->faker->boolean(20),
            'is_active' => true,
        ];
    }
} 