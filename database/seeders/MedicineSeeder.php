<?php

namespace Database\Seeders;

use App\Models\Medicine; // Don't forget to import the Medicine model!
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Medicine::factory()->count(50)->create(); // Or whatever number you want
    }
}