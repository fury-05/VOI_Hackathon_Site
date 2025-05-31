<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Add these for the relationships:
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'team_id',
        'hackathon_id',
        'name',
        'description',
        'github_repo_url',
        'live_url',
        'status',
        'tags',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tags' => 'array', // If you stored tags as JSON
    ];

    // Add Eloquent Relationships below this line

    /**
     * Get the user that owns the project.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the team that owns the project (if any).
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /**
     * Get the hackathon this project is submitted to (if any).
     */
    public function hackathon(): BelongsTo
    {
        return $this->belongsTo(Hackathon::class, 'hackathon_id');
    }

    /**
     * Get the GitHub data associated with the project.
     */
    public function githubData(): HasOne
    {
        return $this->hasOne(GitHubData::class, 'project_id');
    }

    /**
     * Get all of the project's comments.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get all of the scores for the project.
     */
    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'project_id');
    }

    /**
     * Get the winner record for this project (if it won a hackathon).
     */
    public function winnerEntry(): HasOne
    {
        // This assumes a project can only win once or you are interested in a specific win.
        // You might also access winners through the Hackathon model.
        return $this->hasOne(Winner::class, 'project_id');
    }
}
