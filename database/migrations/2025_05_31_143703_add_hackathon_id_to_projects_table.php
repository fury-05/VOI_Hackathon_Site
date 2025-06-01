<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('hackathon_id')->nullable()->after('team_id')->constrained('hackathons')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop foreign key first if your DB requires it (e.g., projects_hackathon_id_foreign)
            // You can get the exact foreign key name from your DB schema viewer or an error message if it fails.
            // Or, more robustly, check if the column exists before trying to drop the foreign key.
            if (Schema::hasColumn('projects', 'hackathon_id')) {
                // Check for foreign key constraint before dropping if necessary
                // This part can be database driver specific for robust down migrations.
                // For simplicity, just dropping the column for now.
                // $table->dropForeign(['hackathon_id']); // Or $table->dropForeign('projects_hackathon_id_foreign');
                $table->dropColumn('hackathon_id');
            }
        });
    }
};
