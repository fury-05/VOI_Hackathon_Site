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
        Schema::create('hackathon_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hackathon_id')->constrained()->onDelete('cascade');

            // A registration can be by an individual user OR a team
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('cascade');

            // The project submitted for this hackathon by this registration
            // This can be filled in upon registration or later.
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');

            $table->timestamp('registered_at')->useCurrent();
            // $table->timestamps(); // Usually 'registered_at' is sufficient

            // Ensure a user doesn't register multiple times for the same hackathon
            $table->unique(['hackathon_id', 'user_id'], 'hackathon_user_unique');

            // Ensure a team doesn't register multiple times for the same hackathon
            $table->unique(['hackathon_id', 'team_id'], 'hackathon_team_unique');

            // Additional constraint: A project should ideally be submitted only once per hackathon
            // This might be better handled at the application level or with a more complex unique index
            // For now, we'll assume one project link per registration.
            // $table->unique(['hackathon_id', 'project_id'], 'hackathon_project_unique'); // Consider if project_id must be unique per hackathon

            // Note: The business logic that either user_id OR team_id must be present (but not both)
            // will typically be enforced in your application's validation logic (e.g., in the Controller or a FormRequest).
            // Database CHECK constraints can do this but are less portable across DB systems with Laravel.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hackathon_registrations');
    }
};
