<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Add these for the relationships:
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'leader_id',
    ];

    // Add Eloquent Relationships below this line

    /**
     * Get the user who is the leader of the team.
     */
    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * The users that belong to the team (members).
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_members', 'team_id', 'user_id')
                    ->withPivot('role', 'joined_at') // Access 'role' and 'joined_at' from the pivot table
                    ->withTimestamps(); // Manages created_at/updated_at on the pivot if they existed by those names.
                                       // For 'joined_at', using withPivot is key.
    }

    /**
     * Get the projects owned by the team.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'team_id');
    }

    /**
     * Get the hackathon registrations for the team.
     */
    public function hackathonRegistrations(): HasMany
    {
        return $this->hasMany(HackathonRegistration::class, 'team_id');
    }
}
