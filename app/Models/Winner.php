<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Add this for the relationships:
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Winner extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hackathon_id',
        'project_id',
        'rank',
        'prize_details',
        'awarded_at', // If you allow manual setting; otherwise, it's set by DB default
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'awarded_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped with created_at/updated_at.
     * Our migration for 'winners' has 'awarded_at' with useCurrent()
     * and did not include $table->timestamps(). So, we set this to false.
     */
    public $timestamps = false;

    // Add Eloquent Relationships below this line

    /**
     * Get the hackathon in which the project won.
     */
    public function hackathon(): BelongsTo
    {
        return $this->belongsTo(Hackathon::class, 'hackathon_id');
    }

    /**
     * Get the project that won.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
