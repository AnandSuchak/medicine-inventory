<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bill; 
use App\Models\Customer; 
use App\Models\Medicine; 
use App\Models\BillItem;
    
class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure customers and medicines exist
        if (Customer::count() === 0) {
            $this->call(CustomerSeeder::class);
        }
        if (Medicine::count() === 0) {
            $this->call(MedicineSeeder::class);
        }

        Bill::factory()->count(50)->create()->each(function ($bill) {
            // For each bill, create between 1 and 5 bill items
            $numberOfItems = rand(1, 5);
            $bill->billItems()->createMany(
                \App\Models\BillItem::factory()->count($numberOfItems)->make([
                    'bill_id' => $bill->id, // Ensure bill_item is linked to this bill
                ])->toArray()
            );

            // Recalculate bill totals based on created items (important for consistency)
            $subTotalBeforeTax = $bill->billItems->sum('sub_total');
            $totalGstAmount = $bill->billItems->sum('item_gst_amount');
            $netAmount = ($subTotalBeforeTax + $totalGstAmount) - $bill->discount_amount;

            $bill->update([
                'sub_total_before_tax' => $subTotalBeforeTax,
                'total_gst_amount' => $totalGstAmount,
                'net_amount' => $netAmount,
            ]);
        });
    }
}
