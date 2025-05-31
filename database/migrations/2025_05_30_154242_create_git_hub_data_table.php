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
        Schema::create('github_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->unique()->constrained()->onDelete('cascade');

            $table->unsignedInteger('commits_count')->default(0);
            $table->unsignedInteger('issues_count')->default(0);
            $table->unsignedInteger('open_issues_count')->default(0);
            $table->unsignedInteger('closed_issues_count')->default(0);
            $table->unsignedInteger('pull_requests_count')->default(0);
            $table->unsignedInteger('open_pull_requests_count')->default(0);
            $table->unsignedInteger('merged_pull_requests_count')->default(0);
            $table->unsignedInteger('stars_count')->default(0);
            $table->unsignedInteger('forks_count')->default(0);
            $table->unsignedInteger('watchers_count')->default(0);
            $table->timestamp('last_commit_at')->nullable();
            $table->unsignedInteger('contributors_count')->default(0);
            $table->json('languages')->nullable();
            $table->longText('raw_data')->nullable();
            // Make this timestamp column nullable
            $table->timestamp('last_fetched_at')->nullable();
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('github_data');
    }
};
