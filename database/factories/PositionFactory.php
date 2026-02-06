<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['pj', 'clt', 'estagio']);

        return [
            'company_id'  => \App\Models\Company::factory(),
            'title'       => fake()->jobTitle(),
            'description' => fake()->paragraph(),
            'type'        => $type,
            'salary'      => in_array($type, ['clt', 'estagio']) ? fake()->numberBetween(1212, 10000) : null,
            'schedule' => in_array($type, ['clt', 'estagio']) ? ($type === 'estagio' ? fake()->randomElement(['4 horas/semana', '6 horas/semana']) : fake()->randomElement(['40 horas/semana', '30 horas/semana', '20 horas/semana'])) : null,
        ];
    }
}
