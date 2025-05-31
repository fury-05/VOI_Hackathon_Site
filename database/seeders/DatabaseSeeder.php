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
        // Call your specific seeders here in the order you want them to run
        $this->call([
            UserSeeder::class,
            ProjectSeeder::class,
            GitHubDataSeeder::class, // <-- Add this line

            // Add other seeders here later (e.g., TeamSeeder, HackathonSeeder, GitHubDataSeeder etc.)
        ]);
    }
}
