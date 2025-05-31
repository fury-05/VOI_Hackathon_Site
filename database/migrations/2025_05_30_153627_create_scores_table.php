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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('judge_id')->constrained('users')->onDelete('cascade'); // The user (judge) who gave the score
            $table->foreignId('criterion_id')->constrained('judging_criteria')->onDelete('cascade');
            $table->foreignId('hackathon_id')->constrained('hackathons')->onDelete('cascade'); // For context and easier querying

            $table->unsignedInteger('points_awarded');
            $table->text('comments')->nullable(); // Judge's specific comments for this score/criterion

            $table->timestamps(); // created_at and updated_at

            // Ensure a judge scores a project's criterion only once
            $table->unique(['project_id', 'judge_id', 'criterion_id'], 'project_judge_criterion_unique_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
