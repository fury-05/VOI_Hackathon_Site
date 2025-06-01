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
            $table->foreignId('project_id')->constrained()->onDelete('cascade');

            // Core GitHub Repo Info
            $table->unsignedBigInteger('github_id')->unique()->nullable()->comment('GitHub numeric ID');
            $table->string('node_id')->nullable()->comment('GitHub global Relay ID');
            $table->string('name')->nullable()->comment('Repository name');
            $table->string('full_name')->nullable()->comment('e.g., username/repository-name');
            $table->string('owner_login')->nullable()->comment('Username of the owner');
            $table->string('owner_avatar_url', 2048)->nullable();
            $table->string('html_url', 2048)->nullable()->comment('URL to the repo on GitHub');
            $table->text('description')->nullable();
            $table->string('language')->nullable()->comment('Primary programming language');
            $table->json('languages_data')->nullable()->comment('JSON object of languages and their byte counts');
            $table->string('license_name')->nullable();
            $table->json('topics')->nullable()->comment('Repository topics/tags from GitHub');

            // Counts & Stats
            $table->integer('stars_count')->default(0);
            $table->integer('watchers_count')->default(0);
            $table->integer('forks_count')->default(0);

            $table->integer('commits_count')->default(0)->nullable(); // Added
            $table->integer('contributors_count')->default(0)->nullable(); // Added

            $table->integer('issues_count')->default(0)->nullable()->comment('Total issues (open + closed)'); // Added
            $table->integer('open_issues_count')->default(0);
            $table->integer('closed_issues_count')->default(0)->nullable(); // Added

            $table->integer('pull_requests_count')->default(0)->nullable()->comment('Total PRs'); // Added
            $table->integer('open_pull_requests_count')->default(0)->nullable(); // Added
            $table->integer('merged_pull_requests_count')->default(0)->nullable(); // Added

            // Timestamps from GitHub
            $table->timestamp('last_commit_at')->nullable()->comment('Timestamp of the last push/commit to default branch');
            $table->timestamp('created_at_gh')->nullable()->comment('Timestamp repo was created on GitHub');
            $table->timestamp('updated_at_gh')->nullable()->comment('Timestamp repo was last updated on GitHub');

            // Data Management
            $table->json('raw_data')->nullable()->comment('Store full API response for debugging or extended info'); // Added
            $table->timestamp('last_fetched_at')->nullable()->comment('When this data was last fetched from API'); // Added

            $table->timestamps(); // Eloquent's created_at and updated_at for this local record
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('github_data');
    }
};
