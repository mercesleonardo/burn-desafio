<?php

namespace Database\Factories;

use App\Enums\CompanyPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => fake()->company(),
            'description' => fake()->paragraph(),
            'cnpj'        => fake()->unique()->numerify('##############'),
            'plan'        => fake()->randomElement(CompanyPlan::cases()),
        ];
    }

    /**
     * Indicate that the company should have a free plan.
     */
    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'plan' => CompanyPlan::FREE,
        ]);
    }

    /**
     * Indicate that the company should have a premium plan.
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'plan' => CompanyPlan::PREMIUM,
        ]);
    }
}
