<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; 

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            // password will be 'password' by default from the factory
        ]);
        User::factory()->count(10)->create();
    }
}
