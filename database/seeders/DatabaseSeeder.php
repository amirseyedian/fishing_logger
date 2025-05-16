<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Optionally create other users
        // User::factory(10)->create();

        // Create a test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // ✅ Create the admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@domain.com',
            'password' => Hash::make('admin123'),
        ]);
    }
}