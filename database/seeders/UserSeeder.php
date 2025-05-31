<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Import the User model
use Illuminate\Support\Facades\Hash; // Import Hash facade for passwords

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a generic user
        User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'), // password
            'github_username' => 'testuser',
            'bio' => 'A passionate developer exploring new technologies.',
            'skills' => json_encode(['Laravel', 'Vue.js', 'API Development']), // Ensure skills are JSON encoded if your model expects an array but DB column is JSON
            'is_admin' => false,
        ]);

        // Create an admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // password
            'github_username' => 'adminhub',
            'bio' => 'Administrator for DevLink Hub.',
            'skills' => json_encode(['PHP', 'MySQL', 'Server Management']),
            'is_admin' => true,
        ]);

        // You can add more users here if you like
        // User::factory()->count(5)->create(); // Alternative using factories if you set them up
    }
}
