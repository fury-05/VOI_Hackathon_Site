<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\GitHubData; // Add this
use App\Services\GitHubService; // Add this
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log; // For logging within the controller if needed
use Carbon\Carbon; // For handling timestamps
use Illuminate\Support\Str; // Though not explicitly used in this version, often useful

class ProjectController extends Controller
{
    /**
     * Display the specified project publicly.
     * Assumes Project model uses ID for route model binding.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function showPublic(Project $project): View
    {
        $project->load(['user', 'team', 'githubData', 'comments.user']);

        // Define or fetch language colors for the ECharts on the project detail page
        $languageColors = [
            'JavaScript' => '#f1e05a',
            'JS' => '#f1e05a', // Alias for JavaScript
            'PHP' => '#4F5D95',
            'HTML' => '#e34c26',
            'CSS' => '#563d7c',
            'Python' => '#3572A5',
            'Java' => '#b07219',
            'TypeScript' => '#2b7489',
            'Ruby' => '#701516',
            'C#' => '#178600',
            'C++' => '#f34b7d',
            'Go' => '#00ADD8',
            'Swift' => '#F05138',
            'Kotlin' => '#7F52FF',
            'Rust' => '#DEA584',
            'Scala' => '#DC322F',
            // Add more common languages and their typical brand/associated colors
            'default' => '#A0AEC0' // A fallback gray color
        ];

        return view('projects.show-public', [
            'project' => $project,
            'languageColors' => $languageColors,
        ]);
    }

    /**
     * Show the form for creating a new project by the authenticated user.
     * This route is protected by 'auth' middleware.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('projects.create');
    }

    /**
     * Store a newly created project in storage for the authenticated user.
     * This route is protected by 'auth' middleware.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, GitHubService $githubService): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'github_repo_url' => ['nullable', 'url', 'max:2048', 'regex:/^https:\/\/github\.com\/[a-zA-Z0-9._-]+\/[a-zA-Z0-9._-]+(\.git)?(\/.*)*$/i'],
            'live_url' => 'nullable|url|max:2048',
            'tags' => 'nullable|string|max:1000',
        ]);

        $projectData = $validatedData;
        $projectData['user_id'] = Auth::id();

        if (!empty($validatedData['tags'])) {
            $tagsArray = array_filter(array_map('trim', explode(',', $validatedData['tags'])));
            $projectData['tags'] = !empty($tagsArray) ? array_values($tagsArray) : null;
        } else {
            $projectData['tags'] = null;
        }

        $projectData['status'] = 'planning'; // Default status

        $project = Project::create($projectData);

        // Now, try to fetch and store GitHub data if a URL was provided
        if ($project && !empty($project->github_repo_url)) {
            $parsedUrl = Project::parseGithubUrl($project->github_repo_url);

            if ($parsedUrl && isset($parsedUrl['owner']) && isset($parsedUrl['repo'])) {
                $repoDetails = $githubService->getRepositoryDetails($parsedUrl['owner'], $parsedUrl['repo']);

                if ($repoDetails) {
                    try {
                        GitHubData::updateOrCreate(
                            ['project_id' => $project->id], // Find by project_id
                            [ // Data to create or update with
                                'github_id' => $repoDetails['id'] ?? null,
                                'node_id' => $repoDetails['node_id'] ?? null,
                                'name' => $repoDetails['name'] ?? null,
                                'full_name' => $repoDetails['full_name'] ?? null,
                                'owner_login' => $repoDetails['owner']['login'] ?? null,
                                'owner_avatar_url' => $repoDetails['owner']['avatar_url'] ?? null,
                                'html_url' => $repoDetails['html_url'] ?? null,
                                'description' => $repoDetails['description'] ?? null,
                                'language' => $repoDetails['language'] ?? null, // Primary language
                                // 'languages_data' requires a separate API call to /languages endpoint usually
                                'stars_count' => $repoDetails['stargazers_count'] ?? 0,
                                'watchers_count' => $repoDetails['watchers_count'] ?? 0, // Or subscribers_count for true "watchers"
                                'forks_count' => $repoDetails['forks_count'] ?? 0,
                                'open_issues_count' => $repoDetails['open_issues_count'] ?? 0,
                                'last_commit_at' => isset($repoDetails['pushed_at']) ? Carbon::parse($repoDetails['pushed_at']) : null,
                                'created_at_gh' => isset($repoDetails['created_at']) ? Carbon::parse($repoDetails['created_at']) : null,
                                'updated_at_gh' => isset($repoDetails['updated_at']) ? Carbon::parse($repoDetails['updated_at']) : null,
                                'license_name' => $repoDetails['license']['name'] ?? null,
                                'topics' => $repoDetails['topics'] ?? [], // 'topics' is usually an array
                                'raw_data' => $repoDetails, // Store the full response
                                'last_fetched_at' => Carbon::now(),
                                // Fields like commits_count, contributors_count, detailed languages_data, etc.,
                                // often require separate API calls. We can add them later.
                            ]
                        );
                        Log::info("GitHub data fetched and saved for project ID: {$project->id}");
                    } catch (\Exception $e) {
                        Log::error("Failed to save GitHub data for project ID: {$project->id} - " . $e->getMessage());
                    }
                } else {
                    Log::warning("Could not fetch GitHub repository details for URL: {$project->github_repo_url} for project ID: {$project->id}");
                }
            } else {
                Log::warning("Could not parse GitHub URL: {$project->github_repo_url} for project ID: {$project->id}");
            }
        }

        return redirect()->route('projects.show-public', $project->id)
                         ->with('success', 'Project "' . $project->name . '" created successfully!');
    }

     /**
     * Re-fetches and updates GitHub data for a specific project.
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Services\GitHubService $githubService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refreshGithubData(Project $project, GitHubService $githubService): RedirectResponse
    {
        if (empty($project->github_repo_url)) {
            return redirect()->route('projects.show-public', $project->id)
                             ->with('error', 'This project does not have a GitHub repository URL configured.');
        }

        $parsedUrl = Project::parseGithubUrl($project->github_repo_url);

        if ($parsedUrl && isset($parsedUrl['owner']) && isset($parsedUrl['repo'])) {
            $repoDetails = $githubService->getRepositoryDetails($parsedUrl['owner'], $parsedUrl['repo']);

            if ($repoDetails) {
                try {
                    GitHubData::updateOrCreate(
                        ['project_id' => $project->id], // Find by project_id
                        [ // Data to create or update with
                            'github_id'         => $repoDetails['id'] ?? null,
                            'node_id'           => $repoDetails['node_id'] ?? null,
                            'name'              => $repoDetails['name'] ?? null,
                            'full_name'         => $repoDetails['full_name'] ?? null,
                            'owner_login'       => $repoDetails['owner']['login'] ?? null,
                            'owner_avatar_url'  => $repoDetails['owner']['avatar_url'] ?? null,
                            'html_url'          => $repoDetails['html_url'] ?? null,
                            'description'       => $repoDetails['description'] ?? null,
                            'language'          => $repoDetails['language'] ?? null,
                            'stars_count'       => $repoDetails['stargazers_count'] ?? 0,
                            'watchers_count'    => $repoDetails['watchers_count'] ?? 0,
                            'forks_count'       => $repoDetails['forks_count'] ?? 0,
                            'open_issues_count' => $repoDetails['open_issues_count'] ?? 0,
                            'last_commit_at'    => isset($repoDetails['pushed_at']) ? Carbon::parse($repoDetails['pushed_at']) : null,
                            'created_at_gh'     => isset($repoDetails['created_at']) ? Carbon::parse($repoDetails['created_at']) : null,
                            'updated_at_gh'     => isset($repoDetails['updated_at']) ? Carbon::parse($repoDetails['updated_at']) : null,
                            'license_name'      => $repoDetails['license']['name'] ?? null,
                            'topics'            => $repoDetails['topics'] ?? [],
                            'raw_data'          => $repoDetails,
                            'last_fetched_at'   => Carbon::now(),
                            // Note: Some fields like specific PR counts, total commits, etc.,
                            // are not directly available from this single API endpoint.
                            // They might be updated if GitHub includes them in the main repo response,
                            // or they'll remain as they were/default.
                        ]
                    );
                    Log::info("GitHub data REFETCHED and saved for project ID: {$project->id}");
                    return redirect()->route('projects.show-public', $project->id)
                                     ->with('success', 'GitHub data refreshed successfully!');
                } catch (\Exception $e) {
                    Log::error("Failed to save REFETCHED GitHub data for project ID: {$project->id} - " . $e->getMessage());
                    return redirect()->route('projects.show-public', $project->id)
                                     ->with('error', 'Could not save refreshed GitHub data.');
                }
            } else {
                Log::warning("Could not REFETCH GitHub repository details for URL: {$project->github_repo_url} for project ID: {$project->id}");
                return redirect()->route('projects.show-public', $project->id)
                                 ->with('error', 'Could not fetch new data from GitHub.');
            }
        } else {
            Log::warning("Could not parse GitHub URL for REFETCH: {$project->github_repo_url} for project ID: {$project->id}");
            return redirect()->route('projects.show-public', $project->id)
                             ->with('error', 'Could not parse the GitHub repository URL for this project.');
        }
    }

    // Future methods for authenticated users to manage THEIR OWN projects:
    // public function myProjects() { /* List user's projects */ }
    // public function edit(Project $project) { /* Show edit form, with authorization */ }
    // public function update(Request $request, Project $project) { /* Update project, with authorization */ }
    // public function destroy(Project $project) { /* Delete project, with authorization */ }
}
