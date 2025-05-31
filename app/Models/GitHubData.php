<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Add this for the relationship:
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GitHubData extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Laravel would typically infer 'git_hub_data' but explicitly defining it
     * ensures it matches our migration if there was any ambiguity.
     * In this case, 'github_data' is standard, so this is optional.
     * @var string
     */
    protected $table = 'github_data'; // Optional, Eloquent should infer this correctly

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'commits_count',
        'issues_count',
        'open_issues_count',
        'closed_issues_count',
        'pull_requests_count',
        'open_pull_requests_count',
        'merged_pull_requests_count',
        'stars_count',
        'forks_count',
        'watchers_count',
        'last_commit_at',
        'contributors_count',
        'languages',
        'raw_data',
        'last_fetched_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */


    protected $casts = [
        'languages' => 'array', // Casts the JSON 'languages' column to a PHP array
        'last_commit_at' => 'datetime',
        'last_fetched_at' => 'datetime',
        // If 'raw_data' is always stored as JSON, you could cast it too:
        // 'raw_data' => 'array',
    ];

    // Add Eloquent Relationships below this line

    /**
     * Get the project that this GitHub data belongs to.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
