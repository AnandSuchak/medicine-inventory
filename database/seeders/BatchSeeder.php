<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Batch;
use App\Models\Supplier;

class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure some suppliers exist before creating batches
        if (Supplier::count() === 0) {
            Supplier::factory()->count(5)->create();
        }

        Batch::factory()->count(30)->create();
    }
}
