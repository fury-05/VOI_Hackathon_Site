<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\GitHubData;
use Carbon\Carbon; // For handling dates

class GitHubDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            // Check if GitHub data already exists to avoid duplicates if seeder is run multiple times
            if (!$project->githubData) {
                GitHubData::create([
                    'project_id' => $project->id,
                    'commits_count' => rand(50, 500),
                    'issues_count' => rand(5, 50),
                    'open_issues_count' => rand(0, 20),
                    'closed_issues_count' => rand(1, 30), // Ensure open + closed can be <= issues_count
                    'pull_requests_count' => rand(10, 100),
                    'open_pull_requests_count' => rand(0, 15),
                    'merged_pull_requests_count' => rand(5, 85),
                    'stars_count' => rand(10, 1000),
                    'forks_count' => rand(5, 200),
                    'watchers_count' => rand(5, 150),
                    'last_commit_at' => Carbon::now()->subDays(rand(0, 30))->subHours(rand(0,23)),
                    'contributors_count' => rand(1, 15),
                    'languages' => json_encode($this->getRandomLanguages()), // Store as JSON string
                    // 'raw_data' => json_encode(['message' => 'Sample raw data placeholder']), // Optional
                    'last_fetched_at' => Carbon::now(),
                ]);
            }
        }
    }

    private function getRandomLanguages(): array
    {
        $allLanguages = ['PHP' => 0, 'JavaScript' => 0, 'HTML' => 0, 'CSS' => 0, 'Python' => 0, 'Java' => 0, 'TypeScript' => 0, 'Ruby' => 0];
        $pickedLanguages = array_rand($allLanguages, rand(2, 4));
        $percentages = [];
        $totalPercentage = 0;

        foreach ((array)$pickedLanguages as $index => $lang) {
            if ($index === count($pickedLanguages) - 1) { // Last language
                $percent = 100 - $totalPercentage;
            } else {
                $percent = rand(10, (int)((100 - $totalPercentage) / (count($pickedLanguages) - $index)));
            }
            $percentages[$lang] = round($percent, 1);
            $totalPercentage += $percent;
        }
        // Ensure total is exactly 100 due to rounding issues
        if ($totalPercentage > 100) {
             $diff = $totalPercentage - 100;
             // simple adjustment on the largest
             arsort($percentages);
             $firstKey = key($percentages);
             $percentages[$firstKey] -= $diff;
        } elseif ($totalPercentage < 100 && count($percentages) > 0) {
            $diff = 100 - $totalPercentage;
            arsort($percentages);
            $firstKey = key($percentages);
            $percentages[$firstKey] += $diff;
        }

        // Ensure percentages are non-negative after adjustment
        foreach($percentages as $lang => $percent) {
            $percentages[$lang] = max(0, round($percent, 1));
        }

        return $percentages;
    }
}
