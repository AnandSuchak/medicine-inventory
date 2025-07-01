<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
                  return [
                'name' => fake()->company(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->numerify('##########'),
                'address' => fake()->address(),
                'gstin' => fake()->optional()->regexify('[A-Z0-9]{15}'), // Optional GSTIN
            ];
    }
}
