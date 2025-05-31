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
        Schema::create('winners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hackathon_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('rank'); // e.g., 1 for 1st place, 2 for 2nd, etc.
            $table->text('prize_details')->nullable(); // Description of the prize won
            $table->timestamp('awarded_at')->useCurrent();
            // $table->timestamps(); // 'awarded_at' usually covers creation time

            // Ensures that within a hackathon, a rank is unique (e.g., only one 1st place)
            $table->unique(['hackathon_id', 'rank']);

            // Ensures that a project can only win one ranked position in a specific hackathon
            $table->unique(['hackathon_id', 'project_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('winners');
    }
};
