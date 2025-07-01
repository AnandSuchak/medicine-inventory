<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Medicine; // Ensure this is present if using it in the doc block

class MedicineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Medicine::class; // Add this line if it's missing for clarity

    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' ' . fake()->randomElement(['Tablet', 'Syrup', 'Capsule', 'Injection']),
            'description' => fake()->sentence(),
            'unit' => fake()->randomElement(['Pcs', 'Bottle', 'Strip']),
            'hsn_code' => fake()->regexify('[0-9]{8}'),
        ];
    }
}