<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CustomerSeeder::class,
            SupplierSeeder::class,
            BatchSeeder::class, // Depends on SupplierSeeder
            BatchMedicineSeeder::class, // Depends on BatchSeeder and MedicineSeeder
            BillSeeder::class, // Depends on CustomerSeeder and MedicineSeeder (via BillItem)
        ]);
    }
}