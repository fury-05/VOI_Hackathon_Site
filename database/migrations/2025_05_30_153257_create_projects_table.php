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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null'); // Foreign key to teams table
            // We'll add hackathon_id later when we create the hackathons table
            $table->string('name');
            $table->text('description');
            $table->string('github_repo_url')->nullable()->unique();
            $table->string('live_url')->nullable();
            $table->string('status', 50)->default('planning');
            $table->json('tags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
