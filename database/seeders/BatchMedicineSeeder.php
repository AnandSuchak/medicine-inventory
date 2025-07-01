<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Batch;
use App\Models\Medicine;
use App\Models\BatchMedicine;

class BatchMedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure some batches and medicines exist
        if (Batch::count() === 0) {
            $this->call(BatchSeeder::class);
        }
        if (Medicine::count() === 0) {
            $this->call(MedicineSeeder::class);
        }

        BatchMedicine::factory()->count(100)->create();
    }
}
