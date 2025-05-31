<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project; // Import Project model
use App\Models\User;    // Import User model to get user IDs

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the users we created (or assume their IDs if created in a fixed order)
        $testUser = User::where('email', 'user@example.com')->first();
        $adminUser = User::where('email', 'admin@example.com')->first();

        if ($testUser) {
            Project::create([
                'user_id' => $testUser->id,
                'name' => 'DevLink Hub Showcase',
                'description' => 'The very platform we are building! A public portfolio for developers and a hackathon hosting site.',
                'github_repo_url' => 'https://github.com/yourusername/devlink-hub-project', // Replace with a real or example URL
                'live_url' => 'http://127.0.0.1:8000',
                'status' => 'active',
                'tags' => ['Laravel', 'PHP', 'TailwindCSS', 'MySQL', 'Web Development'], // Eloquent will cast to JSON
            ]);

            Project::create([
                'user_id' => $testUser->id,
                'name' => 'AI Powered Task Manager',
                'description' => 'A smart task manager that uses AI to prioritize and suggest tasks. Built with Python and React.',
                'github_repo_url' => 'https://github.com/testuser/ai-task-manager',
                'status' => 'planning',
                'tags' => ['Python', 'React', 'AI', 'Productivity'],
            ]);
        }

        if ($adminUser) {
            Project::create([
                'user_id' => $adminUser->id,
                'name' => 'Community Event Portal',
                'description' => 'A portal for managing local community events and volunteer sign-ups. Features event scheduling and notifications.',
                'github_repo_url' => 'https://github.com/adminhub/event-portal',
                'live_url' => '#', // Placeholder
                'status' => 'completed',
                'tags' => ['PHP', 'Community', 'Events', 'Full-Stack'],
            ]);
        }

        // You can add more projects here
        // Or use factories: Project::factory()->count(10)->create(); (after setting up ProjectFactory)
    }
}
