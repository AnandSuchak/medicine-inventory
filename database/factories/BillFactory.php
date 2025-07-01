<?php

namespace Database\Factories;

use App\Models\Bill; 
use App\Models\Medicine;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer; // <--- Add this line
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bill>
 */
class BillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $billDate = fake()->dateTimeBetween('-6 months', 'now');
            $subTotalBeforeTax = fake()->randomFloat(2, 100, 5000);
            $totalGstAmount = fake()->randomFloat(2, 0, $subTotalBeforeTax * 0.18); // Max 18% of subtotal
            $discountAmount = fake()->randomFloat(2, 0, $subTotalBeforeTax * 0.10); // Max 10% discount

            $netAmount = $subTotalBeforeTax + $totalGstAmount - $discountAmount;
            if ($netAmount < 0) $netAmount = 0; // Ensure non-negative

            return [
                'bill_number' => fake()->unique()->numerify('BILL-#########'),
                'bill_date' => $billDate->format('Y-m-d'),
                'customer_id' => Customer::factory(),
                'sub_total_before_tax' => $subTotalBeforeTax,
                'total_gst_amount' => $totalGstAmount,
                'discount_amount' => $discountAmount,
                'net_amount' => $netAmount,
                'payment_status' => fake()->randomElement(['pending', 'paid', 'cancelled']),
                'notes' => fake()->optional()->sentence(),
            ];
    }
}
