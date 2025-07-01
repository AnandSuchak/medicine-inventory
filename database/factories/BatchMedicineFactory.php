<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Medicine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BatchMedicineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
        {
            $quantity = fake()->numberBetween(10, 500);
            $price = fake()->randomFloat(2, 10, 1000);
            $ptr = fake()->randomFloat(2, $price * 0.8, $price * 0.95);
            $expiryDate = fake()->dateTimeBetween('+1 month', '+2 years')->format('Y-m-d');

            return [
                'batch_id' => Batch::factory(), // Creates a new batch
                'medicine_id' => Medicine::factory(), // Creates a new medicine
                'quantity' => $quantity,
                'price' => $price,
                'ptr' => $ptr,
                'expiry_date' => $expiryDate,
            ];
        }
}
