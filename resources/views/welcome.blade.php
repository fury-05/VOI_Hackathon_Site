@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            {{-- Column 1 --}}
            <div class="lg:col-span-1 md:col-span-1 space-y-6">
                {{-- This card now expects $highlightCardData from DashboardController --}}
                @if(isset($highlightCardData) && $highlightCardData['item'])
                    @include('partials.dashboard._highlight_card', $highlightCardData)
                @else
                    {{-- Fallback if $highlightCardData is not set or item is null --}}
                    @include('partials.dashboard._highlight_card', [
                        'item' => [
                            'type' => 'Welcome',
                            'title' => 'DevLink Hub',
                            'subtitle' => 'Explore & Connect!',
                            'description' => 'Discover projects and hackathons.',
                            'url' => '#',
                            'buttonText' => 'Learn More'
                        ]
                    ])
                @endif

                {{-- This card now expects $projectsByTechnologyData from DashboardController --}}
                @if(isset($projectsByTechnologyData))
                    @include('partials.dashboard._list_stat_card', $projectsByTechnologyData)
                @else
                    @include('partials.dashboard._list_stat_card', [
                        'title' => 'Projects by Technology',
                        'items' => [['name' => 'Data loading...', 'value' => '', 'percentage' => '', 'color' => 'bg-gray-400']]
                    ])
                @endif
            </div>

            {{-- Column 2 --}}
            <div class="lg:col-span-2 md:col-span-1 space-y-6">
                @if(isset($platformStats))
                    @include('partials.dashboard._platform_stats_card', $platformStats)
                @endif

                @if(isset($githubActivitySnapshot))
                    @include('partials.dashboard._github_activity_snapshot_card', $githubActivitySnapshot)
                @endif

                @if(isset($weeklyActivityChartData))
                    @include('partials.dashboard._chart_card', $weeklyActivityChartData)
                @endif

                @if(isset($topActiveProjectsData))
                    @include('partials.dashboard._detailed_list_stat_card', $topActiveProjectsData)
                @endif

                {{-- This card now expects $topPlatformContributorsData from DashboardController --}}
                @if(isset($topPlatformContributorsData))
                    @include('partials.dashboard._top_github_contributors_card', $topPlatformContributorsData)
                @else
                    @include('partials.dashboard._top_github_contributors_card', [
                        'title' => 'Top Platform Contributors',
                        'period' => 'This Month (Loading...)',
                        'contributors' => [],
                        'viewAllUrl' => '#'
                    ])
                @endif
            </div>

            {{-- Column 3 --}}
            <div class="lg:col-span-1 md:col-span-2 space-y-6">
                @if(isset($totalInteractionsChartData))
                    @include('partials.dashboard._main_metric_with_chart_card', $totalInteractionsChartData)
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @if(isset($activeHackathonsStat))
                        @include('partials.dashboard._small_stat_card', $activeHackathonsStat)
                    @endif
                    @if(isset($newProjectsStat))
                        @include('partials.dashboard._small_stat_card', $newProjectsStat)
                    @endif
                </div>

                @if(isset($developerSignupsChartData))
                    @include('partials.dashboard._small_bar_chart_card', $developerSignupsChartData)
                @endif

                @if(isset($announcementsData))
                    @include('partials.dashboard._info_list_card', $announcementsData)
                @endif

                @if(isset($recentDiscussionsData))
                    @include('partials.dashboard._info_list_card', $recentDiscussionsData)
                @endif
            </div>
        </div>
    </div>
</div>
@endsection




@push('scripts')
    {{-- We will add script tags here later for Chart.js or other dynamic elements --}}
    {{-- Example for a chart (actual data and init would be more complex) --}}
    script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- <script>
    // Placeholder: Chart rendering logic will go here
    // const ctx = document.getElementById('weeklyActivityChart').getContext('2d');
    // new Chart(ctx, { type: 'bar', data: { labels: ['Mon', 'Tue', 'Wed'], datasets: [{ label: 'Activity', data: [10,20,30]}]} });
</script> --}}
@endpush
