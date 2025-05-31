<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Add these for the relationships:
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JudgingCriterion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Eloquent will infer 'judging_criteria' from 'JudgingCriterion'.
     * Explicitly defining it is optional but can be good for clarity.
     * @var string
     */
    // protected $table = 'judging_criteria'; // Optional

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hackathon_id',
        'name',
        'description',
        'max_points',
        'weight',
    ];

    // Add Eloquent Relationships below this line

    /**
     * Get the hackathon that this criterion belongs to.
     */
    public function hackathon(): BelongsTo
    {
        return $this->belongsTo(Hackathon::class, 'hackathon_id');
    }

    /**
     * Get all the scores given based on this criterion.
     */
    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'criterion_id');
    }
}
