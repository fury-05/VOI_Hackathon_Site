<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Add this for the relationship:
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'admin_id',
        'title',
        'content',
        'published_at',
        'is_pinned',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
        'is_pinned' => 'boolean',
    ];

    // Note: public $timestamps = true; is the default, so no need to set it
    // as our 'announcements' migration included $table->timestamps();

    // Add Eloquent Relationships below this line

    /**
     * Get the admin user who created the announcement.
     */
    public function admin(): BelongsTo
    {
        // Assuming 'admin_id' in the announcements table is a foreign key to the 'id' in the 'users' table.
        return $this->belongsTo(User::class, 'admin_id');
    }
}
