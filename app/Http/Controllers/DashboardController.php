<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Hackathon;
use App\Models\GitHubData;
use App\Models\Announcement;
use App\Models\Comment;
use App\Models\Winner; // For highlight card
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Arr; // For array helper

class DashboardController extends Controller
{
    public function index(): View
    {
        // === Platform Stats ===
        $developerCount = User::count();
        $projectCount = Project::count();
        $hackathonOverallCount = Hackathon::count();
        $totalCommitsSum = GitHubData::sum('commits_count');

        $platformStats = [
            'title' => 'Overall Platform Stats',
            'growthText' => '+'.rand(5,15).'% This Month',
            'stats' => [
                ['label' => 'Developers', 'value' => (string)$developerCount, 'icon_path' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z', 'icon_bg_color' => 'bg-blue-500'],
                ['label' => 'Projects', 'value' => (string)$projectCount, 'icon_path' => 'M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776', 'icon_bg_color' => 'bg-green-500'],
                ['label' => 'Hackathons', 'value' => (string)$hackathonOverallCount, 'icon_path' => 'M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-4.5A3.375 3.375 0 0012.75 9.75H11.25A3.375 3.375 0 007.5 13.125V18.75m9 0h1.5a2.25 2.25 0 100-4.5H7.5M12 12.75a.75.75 0 000-1.5H13.5a.75.75 0 000-1.5H12a.75.75 0 000-1.5H13.5a.75.75 0 000-1.5H12a.75.75 0 00-.75.75v4.5c0 .414.336.75.75.75h1.5a.75.75 0 000-1.5H12a.75.75 0 000-1.5z', 'icon_bg_color' => 'bg-purple-500'],
                ['label' => 'Total Commits', 'value' => number_format($totalCommitsSum), 'icon_path' => 'M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5', 'icon_bg_color' => 'bg-yellow-500']
            ]
        ];

        // === GitHub Activity Snapshot ===
        $totalOpenIssues = GitHubData::sum('open_issues_count');
        $totalOpenPRs = GitHubData::sum('open_pull_requests_count');
        $githubActivitySnapshot = [
            'title' => 'Platform GitHub Snapshot',
            'period' => 'Overall',
            'stats' => [
                ['label' => 'Total Commits', 'value' => number_format($totalCommitsSum), 'icon_path' => 'M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5', 'icon_bg_color' => 'bg-sky-500'],
                ['label' => 'Open Issues', 'value' => number_format($totalOpenIssues), 'icon_path' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z', 'icon_bg_color' => 'bg-red-500'],
                ['label' => 'Open PRs', 'value' => number_format($totalOpenPRs), 'icon_path' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z', 'icon_bg_color' => 'bg-green-500'],
                ['label' => 'Total Projects', 'value' => (string)$projectCount, 'icon_path' => 'M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776', 'icon_bg_color' => 'bg-purple-500'],
            ]
        ];

        // === Small Stats Cards ===
        $activeHackathonCount = Hackathon::whereIn('status', ['ongoing', 'registration_open'])->count();
        $activeHackathonsStat = ['title' => 'Active Hackathons', 'value' => (string)$activeHackathonCount, 'change' => 'View All', 'changeType' => 'link', 'url' => '#'];
        $newProjectsCount = Project::where('created_at', '>=', Carbon::now()->subWeek())->count();
        $newProjectsStat = ['title' => 'New Projects', 'value' => (string)$newProjectsCount, 'change' => 'This Week', 'changeType' => 'neutral'];

        // === Announcements ===
        $recentAnnouncements = Announcement::whereNotNull('published_at')->orderBy('published_at', 'desc')->take(3)->get();
        $announcementsData = [
            'title' => '📢 Announcements',
            'items' => $recentAnnouncements->map(function ($ann) {
                return ['title' => $ann->title, 'subtitle' => substr(strip_tags($ann->content), 0, 50).'...', 'value' => 'Read', 'valueType' => 'link', 'url' => '#'];
            })->toArray(), 'viewAllUrl' => '#'
        ];

        // === Top Active Projects ===
        $topProjects = Project::with('githubData')->whereHas('githubData')->get()->sortByDesc(function($p){ return $p->githubData->commits_count ?? 0; })->take(3);
        $topActiveProjectsData = [
            'title' => 'Top Active Projects',
            'mainValue' => number_format(GitHubData::sum('commits_count') * 0.28) . ' Commits (Est. Top %)',
            'comparisonText' => '+'.rand(5,15).'% vs Last Month',
            'items' => $topProjects->map(function ($p) {
                return ['name' => $p->name, 'details' => $p->description ? substr(strip_tags($p->description),0,50).'...' : 'No desc', 'value' => ($p->githubData->commits_count ?? 0).' Commits', 'icon' => 'rocket', 'url' => route('projects.show-public', $p->id)];
            })->toArray(),
        ];

        // === Data for Chart Cards (Illustrative Time-Series) ===
        $sevenDaysLabels = collect(range(0, 6))->map(fn($i) => Carbon::now()->subDays(6-$i)->format('M d'))->toArray();
        $weeklyActivityChartData = [
            'title' => 'Weekly Platform Activity', 'chartId' => 'weeklyActivityChart',
            'infoText' => 'Engagement <span class="font-semibold text-green-500">+'.rand(5,20).'%</span> this week.', 'buttonText' => 'View Details',
            'chartData' => ['labels' => $sevenDaysLabels, 'datasets' => [
                ['label'=>'New Projects', 'data'=>collect(range(0,6))->map(fn()=>rand(2,15))->toArray(), 'backgroundColor'=>'rgba(109,40,217,0.6)', 'borderColor'=>'rgba(109,40,217,1)', 'borderWidth'=>1, 'borderRadius'=>4, 'type'=>'bar'],
                ['label'=>'New Users', 'data'=>collect(range(0,6))->map(fn()=>rand(5,25))->toArray(), 'backgroundColor'=>'rgba(52,211,153,0.6)', 'borderColor'=>'rgba(52,211,153,1)', 'borderWidth'=>1, 'borderRadius'=>4, 'type'=>'line', 'tension'=>0.3, 'fill'=>false],
            ]],
        ];
        $totalGHInteractions = $totalCommitsSum + GitHubData::sum('issues_count') + GitHubData::sum('pull_requests_count');
        $totalInteractionsChartData = [
            'title' => 'Total GitHub Interactions', 'value' => number_format($totalGHInteractions), 'chartId' => 'totalInteractionsChart',
            'chartData' => ['labels' => $sevenDaysLabels, 'datasets' => [['label'=>'Interactions', 'data'=>collect(range(0,6))->map(fn()=>rand(500,2000))->toArray(), 'borderColor'=>'var(--app-primary-accent)', 'tension'=>0.3, 'fill'=>false, 'type'=>'line']]],
        ];
        $developerSignupsChartData = [
            'title' => 'Developer Sign-ups', 'value' => (string)$developerCount, 'chartId' => 'developerSignupsChart',
            'chartData' => ['labels' => $sevenDaysLabels, 'datasets' => [['label'=>'Sign-ups', 'data'=>collect(range(0,6))->map(fn()=>rand(10,50))->toArray(), 'backgroundColor'=>'var(--app-primary-accent)', 'borderColor'=>'var(--app-primary-accent)', 'borderWidth'=>1, 'barThickness'=>6, 'type'=>'bar']]],
        ];

        // === Recent Discussions (Comments) ===
        $recentComments = Comment::with('user', 'commentable')->orderBy('created_at', 'desc')->take(3)->get();
        $recentDiscussionsData = [
            'title' => '💬 Recent Discussions',
            'items' => $recentComments->map(function ($c) {
                $cTitle = 'on an item';
                if($c->commentable){
                    if($c->commentable_type === Project::class) $cTitle = 'on Project: '.($c->commentable->name ?? 'N/A');
                    elseif($c->commentable_type === Hackathon::class) $cTitle = 'on Hackathon: '.($c->commentable->name ?? 'N/A');
                }
                return ['title'=>$c->user->name??'Anonymous', 'subtitle'=>substr(strip_tags($c->content),0,60).'... '.$cTitle, 'value'=>$c->created_at->diffForHumans(), 'valueType'=>'text'];
            })->toArray(), 'viewAllUrl' => '#',
        ];

        // === Highlight Card Data ===
        // Example: Feature the latest winning project, or newest active hackathon
        $highlightedItem = null;
        $latestWinner = Winner::with('project.user')->orderBy('awarded_at', 'desc')->first();
        if ($latestWinner && $latestWinner->project) {
            $highlightedItem = [
                'type' => 'Project Winner',
                'title' => $latestWinner->project->name,
                'subtitle' => 'Won ' . ($latestWinner->hackathon->name ?? 'a recent hackathon') . '!',
                'description' => 'By ' . ($latestWinner->project->user->name ?? 'N/A'),
                'url' => route('projects.show-public', $latestWinner->project->id),
                'buttonText' => 'View Project',
            ];
        } else {
            $latestActiveHackathon = Hackathon::whereIn('status', ['ongoing', 'registration_open'])->orderBy('start_datetime', 'desc')->first();
            if ($latestActiveHackathon) {
                $highlightedItem = [
                    'type' => 'Active Hackathon',
                    'title' => $latestActiveHackathon->name,
                    'subtitle' => 'Starts ' . Carbon::parse($latestActiveHackathon->start_datetime)->format('M d, Y') . '!',
                    'description' => substr(strip_tags($latestActiveHackathon->description), 0, 70) . '...',
                    'url' => '#', // Placeholder for hackathon detail page URL
                    'buttonText' => 'View Hackathon',
                ];
            }
        }
        $highlightCardData = $highlightedItem ? [ // Ensure it's an array structure expected by the partial
            'item' => $highlightedItem // The partial will need to be updated to use $item['title'] etc.
        ] : null;


        // === Projects by Technology (Illustrative - complex to make fully dynamic here) ===
        $projectsByTechnologyData = [
            'title' => 'Projects by Technology',
            'items' => [
                ['name' => 'JavaScript', 'value' => rand(800,1500).' Projects', 'percentage' => rand(25,40).'.'.rand(0,9).'%', 'color' => 'bg-yellow-400'],
                ['name' => 'Python', 'value' => rand(600,1200).' Projects', 'percentage' => rand(20,30).'.'.rand(0,9).'%', 'color' => 'bg-blue-500'],
                ['name' => 'Java', 'value' => rand(400,900).' Projects', 'percentage' => rand(15,25).'.'.rand(0,9).'%', 'color' => 'bg-red-500'],
                ['name' => 'PHP', 'value' => rand(300,700).' Projects', 'percentage' => rand(10,20).'.'.rand(0,9).'%', 'color' => 'bg-indigo-500'],
            ]
        ];

        // === Top Platform Contributors (Illustrative - complex to make fully dynamic here) ===
        $dummyContributors = [];
        $firstNames = ['Elena', 'Marcus', 'Layla', 'Sam', 'Polly', 'Alex', 'Jordan', 'Chris'];
        $lastNames = ['Coder', 'Script', 'Dev', 'Fixit', 'Push', 'Innovate', 'Builds', 'Logic'];
        for ($i=0; $i < 5; $i++) {
            $name = Arr::random($firstNames) . ' ' . Arr::random($lastNames);
            $dummyContributors[] = [
                'name' => $name,
                'avatar_initial' => strtoupper(substr($firstNames[$i % count($firstNames)], 0, 1) . substr($lastNames[$i % count($lastNames)], 0, 1)),
                'metric_value' => rand(90, 320),
                'metric_label' => 'commits',
                'profile_url' => '#'
            ];
        }
        usort($dummyContributors, fn($a, $b) => ($b['metric_value'] ?? 0) <=> ($a['metric_value'] ?? 0));
        $topPlatformContributorsData = [
            'title' => 'Top Platform Contributors',
            'period' => 'This Month (Illustrative)',
            'contributors' => $dummyContributors,
            'viewAllUrl' => '#'
        ];


        return view('welcome', compact(
            'platformStats',
            'githubActivitySnapshot',
            'activeHackathonsStat',
            'newProjectsStat',
            'announcementsData',
            'topActiveProjectsData',
            'weeklyActivityChartData',
            'totalInteractionsChartData',
            'developerSignupsChartData',
            'recentDiscussionsData',
            'highlightCardData', // Added
            'projectsByTechnologyData', // Added
            'topPlatformContributorsData' // Added
        ));
    }
}
