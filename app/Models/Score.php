<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Add this for the relationships:
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Score extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'judge_id',
        'criterion_id',
        'hackathon_id',
        'points_awarded',
        'comments',
    ];

    // Add Eloquent Relationships below this line

    /**
     * Get the project that this score belongs to.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get the judge (user) who gave this score.
     */
    public function judge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    /**
     * Get the judging criterion for which this score was given.
     */
    public function criterion(): BelongsTo
    {
        return $this->belongsTo(JudgingCriterion::class, 'criterion_id');
    }

    /**
     * Get the hackathon to which this score pertains.
     */
    public function hackathon(): BelongsTo
    {
        return $this->belongsTo(Hackathon::class, 'hackathon_id');
    }
}
