<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Add these for the relationships:
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HackathonRegistration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hackathon_id',
        'user_id',
        'team_id',
        'project_id',
        'registered_at', // Though 'registered_at' is often handled by default in migration, if you allow it to be set manually
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'registered_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     * We have 'registered_at' but not the default 'created_at'/'updated_at'
     * unless you specifically added $table->timestamps() in its migration.
     * If you only have 'registered_at' and it's set with useCurrent() or manually,
     * you might set public $timestamps = false; if you don't want Laravel to manage created_at/updated_at.
     * However, our migration for hackathon_registrations did not include $table->timestamps();
     * it only had $table->timestamp('registered_at')->useCurrent();
     * So, we should probably set $timestamps to false.
     */
    public $timestamps = false; // Set to false if you don't have created_at/updated_at columns

    // Add Eloquent Relationships below this line

    /**
     * Get the hackathon that this registration belongs to.
     */
    public function hackathon(): BelongsTo
    {
        return $this->belongsTo(Hackathon::class, 'hackathon_id');
    }

    /**
     * Get the user that this registration belongs to (if individual).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the team that this registration belongs to (if team).
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /**
     * Get the project associated with this registration.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
