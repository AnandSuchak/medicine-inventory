<?php

namespace Database\Factories;

use App\Models\Bill; 
use App\Models\Medicine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BillItem>
 */
class BillItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      $quantity = fake()->numberBetween(1, 10);
            $unitPrice = fake()->randomFloat(2, 5, 500); // Price per unit BEFORE GST
            $subTotal = $quantity * $unitPrice; // Taxable value

            $gstRate = fake()->randomElement([0.00, 5.00, 12.00, 18.00]); // Include 0% for some items
            $itemGstAmount = $subTotal * ($gstRate / 100);
            $totalAmountAfterTax = $subTotal + $itemGstAmount;

            return [
                'bill_id' => Bill::factory(), // Creates a new bill
                'medicine_id' => Medicine::factory(), // Creates a new medicine
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'gst_rate_percentage' => $gstRate,
                'item_gst_amount' => $itemGstAmount,
                'sub_total' => $subTotal,
                'total_amount_after_tax' => $totalAmountAfterTax,
            ];
    }
}
