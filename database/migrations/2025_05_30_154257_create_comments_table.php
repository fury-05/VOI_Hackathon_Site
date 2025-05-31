<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The user who wrote the comment

            // For Polymorphic relationship: commentable_id and commentable_type
            // This will create `commentable_id` (BIGINT UNSIGNED) and `commentable_type` (VARCHAR)
            $table->morphs('commentable');

            // For threaded comments (replies)
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');

            $table->text('content');
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
