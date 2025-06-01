<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; // Ensure this is imported
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Comment;


class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'team_id',
        'hackathon_id',
        'name',
        'description',
        'github_repo_url',
        'live_url',
        'tags',
        'status',
        // Add other fillable fields for your Project model here
    ];

    protected $casts = [
        'tags' => 'array',
        // Add other casts for your Project model here
    ];

    /**
     * Get the team that owns the project (if applicable).
     */
    public function team(): BelongsTo // Add this method
    {
        // Make sure you have a Team model at App\Models\Team
        // And that your 'projects' table has a 'team_id' foreign key column
        return $this->belongsTo(Team::class);
    }
    /**
     * Get the user that owns the project.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments for the project.
     */
    public function comments(): MorphMany  // This might be around line 58 in your file
    {
        // Ensure this line passes BOTH arguments: Comment::class AND 'commentable'
        return $this->morphMany(Comment::class, 'commentable');
    }

    // Add other existing relationships like team, hackathonRegistration etc.
    // Example:
    // public function team(): BelongsTo
    // {
    //     return $this->belongsTo(Team::class);
    // }

    /**
     * Get the GitHub data associated with the project.
     */
    public function githubData(): HasOne
    {
        return $this->hasOne(GitHubData::class);
    }

    /**
     * Parses a GitHub URL to extract owner and repository name.
     *
     * @param string $url The GitHub repository URL.
     * @return array|null An array with 'owner' and 'repo' keys, or null if parsing fails.
     */
    public static function parseGithubUrl(string $url): ?array
    {
        $trimmedUrl = trim($url);
        if (empty($trimmedUrl)) {
            return null;
        }

        // Regex to capture owner and repo from various GitHub URL formats
        // Handles:
        // - https://github.com/owner/repo
        // - http://github.com/owner/repo
        // - github.com/owner/repo
        // - https://github.com/owner/repo.git
        // - https://github.com/owner/repo/
        // - https://github.com/owner/repo/tree/branch
        // - https://github.com/owner/repo/issues etc.
        $pattern = '/^(?:https?:\/\/)?(?:www\.)?github\.com\/([a-zA-Z0-9._-]+)\/([a-zA-Z0-9._-]+)(?:\.git)?(?:\/.*)*$/i';

        if (preg_match($pattern, $trimmedUrl, $matches)) {
            if (count($matches) >= 3) {
                return ['owner' => $matches[1], 'repo' => $matches[2]];
            }
        }

        // Fallback for simpler parsing if regex fails or for non-standard URLs (less robust)
        $path = parse_url($trimmedUrl, PHP_URL_PATH);
        if (empty($path)) {
            // Try to handle cases like "github.com/owner/repo" without scheme
            if (strpos($trimmedUrl, 'github.com/') === 0) {
                $path = substr($trimmedUrl, strlen('github.com/'));
            } else {
                 return null;
            }
        }

        $path = trim($path, '/');
        if (strtolower(substr($path, -4)) === '.git') {
            $path = substr($path, 0, -4);
        }

        $parts = explode('/', $path);
        if (count($parts) >= 2) {
            return ['owner' => $parts[0], 'repo' => $parts[1]];
        }

        return null;
    }
}
