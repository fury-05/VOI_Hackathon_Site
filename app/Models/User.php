<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Keep if you plan to use email verification
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Add these for the relationships we're about to define:
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable // implements MustVerifyEmail (if using)
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'github_username',
        'bio',
        'skills',
        'avatar_url',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'skills' => 'array', // If you stored skills as JSON
            'is_admin' => 'boolean',
        ];
    }

    // Add Eloquent Relationships below this line

    /**
     * Get the projects owned by the user.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'user_id');
    }
/**
 * Set the user's skills.
 * Converts a comma-separated string to an array before saving (Eloquent will then JSON encode it).
 *
 * @param  string|array|null  $value
 * @return void
 */

    /**
     * Get the teams led by the user.
     */
    public function ledTeams(): HasMany
    {
        return $this->hasMany(Team::class, 'leader_id');
    }

    /**
     * The teams that the user belongs to.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_members', 'user_id', 'team_id')
                    ->withPivot('role') // If you want to access the 'role' in the pivot table
                    ->withTimestamps(); // If your pivot table has created_at/updated_at (team_members has joined_at, which is fine)
    }

    /**
     * Get the hackathons created by the user (if they are an admin).
     */
    public function createdHackathons(): HasMany
    {
        // This assumes 'admin_id' on hackathons table links to a user_id
        return $this->hasMany(Hackathon::class, 'admin_id');
    }

    /**
     * Get the hackathon registrations for the user (individual registrations).
     */
    public function hackathonRegistrations(): HasMany
    {
        return $this->hasMany(HackathonRegistration::class, 'user_id');
    }

    /**
     * Get the comments made by the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    /**
     * Get the announcements made by the user (if they are an admin).
     */
    public function announcements(): HasMany
    {
        // This assumes 'admin_id' on announcements table links to a user_id
        return $this->hasMany(Announcement::class, 'admin_id');
    }

    /**
     * The hackathons for which the user is a judge.
     */
    public function judgingHackathons(): BelongsToMany
    {
        return $this->belongsToMany(Hackathon::class, 'judges', 'user_id', 'hackathon_id')
                    ->withTimestamps(); // If your 'judges' pivot table has timestamps (it has assigned_at)
    }

    /**
     * Get the scores given by the user (as a judge).
     */
    public function givenScores(): HasMany
    {
        return $this->hasMany(Score::class, 'judge_id');
    }
}
