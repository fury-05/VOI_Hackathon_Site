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
        Schema::create('judging_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hackathon_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "Innovation", "Technical Implementation", "Presentation"
            $table->text('description')->nullable(); // Detailed explanation of the criterion
            $table->unsignedInteger('max_points'); // Maximum points awardable for this criterion
            $table->float('weight')->default(1.0); // For weighted scoring systems, default to 1 (no specific weight)
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('judging_criteria');
    }
};
