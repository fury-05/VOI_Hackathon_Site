<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Add these for the relationships:
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Hackathon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'admin_id',
        'name',
        'slug',
        'description',
        'rules',
        'prizes',
        'start_datetime',
        'end_datetime',
        'registration_opens_at',
        'registration_closes_at',
        'status',
        'banner_image_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'registration_opens_at' => 'datetime',
        'registration_closes_at' => 'datetime',
    ];

    // Add Eloquent Relationships below this line

    /**
     * Get the admin user who created the hackathon.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get all registrations for this hackathon.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(HackathonRegistration::class, 'hackathon_id');
    }

    /**
     * Get all projects submitted to this hackathon.
     * This assumes projects have a direct hackathon_id foreign key.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'hackathon_id');
    }

    /**
     * The users that are judges for this hackathon.
     */
    public function judges(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'judges', 'hackathon_id', 'user_id')
                    ->withPivot('assigned_at') // Access 'assigned_at' from the pivot table
                    ->withTimestamps(); // Manages created_at/updated_at on the pivot if they existed by those names.
    }

    /**
     * Get all judging criteria for this hackathon.
     */
    public function judgingCriteria(): HasMany
    {
        return $this->hasMany(JudgingCriterion::class, 'hackathon_id');
    }

    /**
     * Get all scores awarded in this hackathon.
     */
    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'hackathon_id');
    }

    /**
     * Get all winners for this hackathon.
     */
    public function winners(): HasMany
    {
        return $this->hasMany(Winner::class, 'hackathon_id');
    }

    /**
     * Get all of the hackathon's comments.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get the route key for the model.
     * Allows using 'slug' for route model binding instead of 'id'.
     * e.g., Route::get('/hackathons/{hackathon}', ...);
     * $hackathon will be resolved by its slug.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
