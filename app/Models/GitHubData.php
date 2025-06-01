<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GitHubData extends Model
{
    use HasFactory;

    protected $table = 'github_data';

    protected $fillable = [
        'project_id',
        'github_id',
        'node_id',
        'name',
        'full_name',
        'owner_login',
        'owner_avatar_url',
        'html_url',
        'description',
        'language',
        'languages_data', // Stores multiple languages and their usage
        'license_name',
        'topics',
        'stars_count',
        'watchers_count',
        'forks_count',
        'commits_count',
        'contributors_count',
        'issues_count',
        'open_issues_count',
        'closed_issues_count',
        'pull_requests_count',
        'open_pull_requests_count',
        'merged_pull_requests_count',
        'last_commit_at',
        'created_at_gh',
        'updated_at_gh',
        'raw_data',
        'last_fetched_at',
    ];

    protected $casts = [
        'languages_data' => 'array',
        'topics' => 'array',
        'raw_data' => 'array', // Assuming API response is JSON
        'last_commit_at' => 'datetime',
        'created_at_gh' => 'datetime',
        'updated_at_gh' => 'datetime',
        'last_fetched_at' => 'datetime',
    ];

    /**
     * Get the project that owns this GitHub data.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
