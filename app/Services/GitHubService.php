<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // For logging errors

class GitHubService
{
    protected string $apiBaseUrl;
    protected ?string $apiToken;

    public function __construct()
    {
        $this->apiBaseUrl = 'https://api.github.com';
        // Fetches the token from config/services.php, which reads from .env
        $this->apiToken = config('services.github.token');
    }

    /**
     * Fetches repository details from GitHub.
     *
     * @param string $owner
     * @param string $repo
     * @return array|null The repository data as an associative array, or null on failure.
     */
    public function getRepositoryDetails(string $owner, string $repo): ?array
    {
        // TEMPORARY DEBUG:
     //dd($this->apiToken);
    // Make sure to remove this after debugging!
        if (empty($this->apiToken)) {
            Log::error('GitHub API token is not configured.');
            return null;
        }

        $url = "{$this->apiBaseUrl}/repos/{$owner}/{$repo}";

        try {
            $response = Http::withToken($this->apiToken)
                            ->accept('application/vnd.github.v3+json') // Recommended by GitHub
                            ->timeout(15) // Set a reasonable timeout (seconds)
                            ->get($url);

            if ($response->successful()) {
                return $response->json(); // Returns the JSON response as an array
            }

            // Log different error types
            if ($response->notFound()) {
                Log::warning("GitHubService: Repository not found for {$owner}/{$repo}. Status: " . $response->status());
            } elseif ($response->clientError()) {
                // 4xx errors: Bad request, unauthorized (e.g. token issues), forbidden, etc.
                Log::error("GitHubService: Client error fetching {$owner}/{$repo}. Status: " . $response->status(), [
                    'response_body' => $response->body() // Be careful logging full body if sensitive
                ]);
            } elseif ($response->serverError()) {
                // 5xx errors: GitHub server issues
                Log::error("GitHubService: Server error fetching {$owner}/{$repo}. Status: " . $response->status());
            } else {
                Log::error("GitHubService: Failed to fetch repository {$owner}/{$repo}. Status: " . $response->status());
            }

            return null;

        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Connection errors, timeouts not caught by HTTP status codes etc.
            Log::error("GitHubService: RequestException for {$owner}/{$repo} - " . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            // Other general exceptions
            Log::critical("GitHubService: Unexpected error for {$owner}/{$repo} - " . $e->getMessage());
            return null;
        }
    }

    // We can add more methods here later for:
    // - getLanguages(string $owner, string $repo)
    // - getContributors(string $owner, string $repo)
    // - getCommits(string $owner, string $repo)
    // etc.
}
