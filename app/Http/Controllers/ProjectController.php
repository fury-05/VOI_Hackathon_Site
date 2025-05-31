<?php

namespace App\Http\Controllers;

use App\Models\Project; // Import the Project model
use Illuminate\Http\Request;
use Illuminate\View\View; // Import View

class ProjectController extends Controller
{
    // ... other methods you might have or will add for logged-in users (create, store, edit, etc.)

    /**
     * Display the specified project publicly.
     */
    public function showPublic(Project $project): View
    {
        $project->load(['user', 'team', 'githubData', 'comments.user']);

        $languageColors = [
            'JavaScript' => '#f1e05a',
            'JS' => '#f1e05a',
            'PHP' => '#4F5D95',
            'HTML' => '#e34c26',
            'CSS' => '#563d7c',
            'Python' => '#3572A5',
            'Java' => '#b07219',
            'TypeScript' => '#2b7489',
            'Ruby' => '#701516',
            // Add more colors as needed
        ];

        return view('projects.show-public', [
            'project' => $project,
            'languageColors' => $languageColors, // Pass the colors to the view
        ]);
    }
}
