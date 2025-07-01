<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Batch>
 */
class BatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
                    return [
                'batch_number' => fake()->unique()->bothify('BATCH-#####-??'),
                'purchase_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                'supplier_id' => Supplier::factory(), // Creates a new supplier or uses an existing one
            ];
    }
}
