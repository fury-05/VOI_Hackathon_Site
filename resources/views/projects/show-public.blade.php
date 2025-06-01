@extends('layouts.app')

@section('content')
    <div class="py-8 md:py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Display Session Success Messages --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-700 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-100 px-4 py-3 rounded relative app-card" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            {{-- End Display Session Success Messages --}}

            <div class="app-card rounded-lg shadow-xl overflow-hidden">
                <div class="p-6 md:p-8">
                    {{-- Project Header --}}
                    <div class="mb-6">
                        <h1 class="text-3xl md:text-4xl font-bold text-primary-accent mb-2">{{ $project->name }}</h1>
                        <div class="text-sm text-[var(--app-text-secondary)]">
                            <span>By: {{ $project->user->name ?? 'N/A' }}</span>
                            @if ($project->team) {{-- Assuming you have the team relationship defined in Project model --}}
                                <span>| Team: {{ $project->team->name }}</span>
                            @endif
                            @if ($project->hackathon) {{-- Assuming you have hackathon relationship via hackathon_id --}}
                                <span class="block sm:inline mt-1 sm:mt-0 sm:ml-2">| Submitted to:
                                    <a href="#" {{-- Replace # with actual link to hackathon detail page if available --}}
                                       class="text-primary-accent hover:underline">{{ $project->hackathon->name }}</a>
                                </span>
                            @endif
                        </div>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @if ($project->githubData && $project->githubData->html_url)
                                <a href="{{ $project->githubData->html_url }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-block bg-gray-700 dark:bg-gray-600 text-white text-xs font-semibold py-1.5 px-3 rounded-full hover:bg-gray-600 dark:hover:bg-gray-500 transition">
                                    <i class="fab fa-github mr-1"></i> GitHub Repo ({{ $project->githubData->full_name }})
                                </a>
                            @elseif ($project->github_repo_url) {{-- Fallback to stored URL if githubData not fully loaded yet or html_url missing --}}
                                 <a href="{{ $project->github_repo_url }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-block bg-gray-700 dark:bg-gray-600 text-white text-xs font-semibold py-1.5 px-3 rounded-full hover:bg-gray-600 dark:hover:bg-gray-500 transition">
                                    <i class="fab fa-github mr-1"></i> GitHub Repo
                                </a>
                            @endif
                            @if ($project->live_url)
                                <a href="{{ $project->live_url }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-block bg-primary-accent text-white text-xs font-semibold py-1.5 px-3 rounded-full hover:opacity-90 transition">
                                    <i class="fas fa-external-link-alt mr-1"></i> Live Demo
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Project Description (from your app) --}}
                    <div class="prose prose-sm sm:prose-base dark:prose-invert max-w-none mb-6 text-[var(--app-text-primary)]">
                        <h2 class="text-xl font-semibold text-[var(--app-text-primary)] border-b border-[var(--app-border-color)] pb-2 mb-3">
                            Project Description
                        </h2>
                        {!! nl2br(e($project->description)) !!}
                    </div>

                    {{-- GitHub Provided Description --}}
                    @if ($project->githubData && $project->githubData->description)
                        <div class="prose prose-sm sm:prose-base dark:prose-invert max-w-none mb-8 text-[var(--app-text-primary)]">
                            <h2 class="text-xl font-semibold text-[var(--app-text-primary)] border-b border-[var(--app-border-color)] pb-2 mb-3">
                                Description from GitHub
                            </h2>
                            <p>{{ $project->githubData->description }}</p>
                        </div>
                    @endif


                    {{-- Add this button, for example, within the GitHub Stats Section or Project Header --}}
@if ($project->githubData)
    <div class="my-4"> {{-- Adjust margin as needed --}}
        <form action="{{ route('projects.refreshGithub', $project->id) }}" method="POST">
            @csrf
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-500 dark:bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 dark:hover:bg-blue-700 active:bg-blue-700 dark:active:bg-blue-800 focus:outline-none focus:border-blue-700 dark:focus:border-blue-800 focus:ring ring-blue-300 dark:ring-blue-700 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 00-15.357-2m15.357 2H15"></path></svg>
                Refresh GitHub Data
            </button>
        </form>
    </div>
@endif



                    {{-- GitHub Stats Section --}}
                    @if ($project->githubData)
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-[var(--app-text-primary)] border-b border-[var(--app-border-color)] pb-2 mb-4">
                                GitHub Statistics
                            </h2>
                            <div class="space-y-6">
                                {{-- Row 1: Core Repo Stats --}}
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                    <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                        <div class="flex items-center text-primary-accent">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.116 3.986 1.241 5.375c.216 1.004-.97 1.758-1.86 1.214l-4.796-2.796-4.796 2.796c-.89.544-2.076-.21-1.86-1.214l1.241-5.375L1.646 11.049c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" /></svg>
                                            <span class="text-sm font-medium text-[var(--app-text-secondary)]">Stars</span>
                                        </div>
                                        <p class="text-2xl font-bold text-[var(--app-text-primary)] mt-1">{{ $project->githubData->stars_count ?? '0' }}</p>
                                    </div>
                                    <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                        <div class="flex items-center text-primary-accent">
                                            {{-- Using a generic "branch" or "repo-forked" icon for forks from Heroicons or FontAwesome --}}
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.293 4.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H5a1 1 0 110-2h9.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg> {{-- Placeholder, replace with better fork icon --}}
                                            <span class="text-sm font-medium text-[var(--app-text-secondary)]">Forks</span>
                                        </div>
                                        <p class="text-2xl font-bold text-[var(--app-text-primary)] mt-1">{{ $project->githubData->forks_count ?? '0' }}</p>
                                    </div>
                                    <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                        <div class="flex items-center text-primary-accent">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2"><path d="M12 15a3 3 0 100-6 3 3 0 000 6z" /><path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd" /></svg>
                                            <span class="text-sm font-medium text-[var(--app-text-secondary)]">Watchers</span>
                                        </div>
                                        <p class="text-2xl font-bold text-[var(--app-text-primary)] mt-1">{{ $project->githubData->watchers_count ?? '0' }}</p>
                                    </div>
                                </div>

                                {{-- Row 2: Activity Stats --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                    <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                        <p class="text-sm font-medium text-[var(--app-text-secondary)]">Primary Language</p>
                                        @if($project->githubData->language)
                                            <p class="text-xl font-bold text-[var(--app-text-primary)] mt-1 flex items-center">
                                                <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: {{ $languageColors[ucfirst(strtolower($project->githubData->language))] ?? $languageColors['default'] }};"></span>
                                                {{ $project->githubData->language }}
                                            </p>
                                        @else
                                            <p class="text-xl font-bold text-[var(--app-text-primary)] mt-1">N/A</p>
                                        @endif
                                    </div>
                                    <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                        <p class="text-sm font-medium text-[var(--app-text-secondary)]">Last Push</p>
                                        @if($project->githubData->last_commit_at)
                                            <p class="text-xl font-bold text-[var(--app-text-primary)] mt-1" title="{{ $project->githubData->last_commit_at->format('M d, Y H:i A') }}">
                                                {{ $project->githubData->last_commit_at->diffForHumans() }}
                                            </p>
                                        @else
                                            <p class="text-xl font-bold text-[var(--app-text-primary)] mt-1">N/A</p>
                                        @endif
                                    </div>
                                     <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                        <p class="text-sm font-medium text-[var(--app-text-secondary)]">Open Issues</p>
                                        <p class="text-2xl font-bold text-[var(--app-text-primary)] mt-1">{{ $project->githubData->open_issues_count ?? '0' }}</p>
                                    </div>
                                </div>

                                {{-- Display GitHub Topics --}}
                                @if($project->githubData->topics && count($project->githubData->topics) > 0)
                                <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                    <p class="text-sm font-medium text-[var(--app-text-secondary)] mb-2">GitHub Topics</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($project->githubData->topics as $topic)
                                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full dark:bg-blue-700 dark:text-blue-200">{{ $topic }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                {{-- Languages Section with ECharts --}}
                                {{-- Note: $project->githubData->languages_data needs to be populated by a separate API call to /languages --}}
                                @if ($project->githubData && $project->githubData->languages_data)
                                    @php
                                        $languagesDataString = $project->githubData->languages_data;
                                        $languagesArray = is_string($languagesDataString) ? json_decode($languagesDataString, true) : (is_array($languagesDataString) ? $languagesDataString : []);

                                        // Calculate total bytes for percentage calculation
                                        $totalBytes = 0;
                                        if(is_array($languagesArray)) {
                                            foreach($languagesArray as $bytes) {
                                                if(is_numeric($bytes)) $totalBytes += $bytes;
                                            }
                                        }
                                    @endphp

                                    @if (is_array($languagesArray) && !empty($languagesArray) && $totalBytes > 0)
                                        <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                            <h4 class="text-sm font-medium text-[var(--app-text-secondary)] mb-3">Languages Used (from /languages endpoint)</h4>
                                            <div class="flex flex-col md:flex-row items-center md:items-start md:space-x-4">
                                                <div id="echartsLanguagesChart" style="width: 160px; height: 160px;" class="flex-shrink-0 mx-auto md:mx-0"></div>
                                                <ul class="list-none p-0 m-0 mt-4 md:mt-0 flex-grow w-full md:w-auto space-y-1">
                                                    @foreach ($languagesArray as $lang => $bytes)
                                                        @if(is_numeric($bytes) && $bytes > 0)
                                                            @php
                                                                $percentage = ($totalBytes > 0) ? ($bytes / $totalBytes) * 100 : 0;
                                                                $colorValue = $languageColors[$lang] ?? ($languageColors[ucfirst(strtolower($lang))] ?? '#cccccc');
                                                            @endphp
                                                            <li class="text-xs flex items-center py-0.5">
                                                                <span class="inline-block w-2.5 h-2.5 rounded-full mr-2 flex-shrink-0" style="background-color: {{ $colorValue }};"></span>
                                                                <span class="text-[var(--app-text-secondary)] mr-1">{{ $lang }}:</span>
                                                                <span class="font-medium text-[var(--app-text-primary)]">{{ number_format($percentage, 1) }}%</span>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @else
                                        <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                         <p class="text-xs text-[var(--app-text-secondary)]">Detailed language data (for chart) not available or not yet fetched. This requires a call to the GitHub /languages endpoint.</p>
                                        </div>
                                    @endif
                                @else
                                     <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                        <p class="text-xs text-[var(--app-text-secondary)]">Detailed language data (for chart) not available.</p>
                                     </div>
                                @endif
                                {{-- End of Languages Section --}}
                            </div>
                            <p class="mt-6 text-xs text-[var(--app-text-secondary)] text-right">
                                GitHub stats last fetched: {{ $project->githubData->last_fetched_at ? $project->githubData->last_fetched_at->diffForHumans() : 'N/A' }}
                            </p>
                        </div>
                    @else
                        <p class="text-[var(--app-text-secondary)] my-6">GitHub data is not available for this project yet or could not be fetched.</p>
                    @endif

                    {{-- Your Existing Tags Section (Keep as is) --}}
                    @if ($project->tags && count($project->tags) > 0)
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-[var(--app-text-primary)] border-b border-[var(--app-border-color)] pb-2 mb-3">
                                Project Tags
                            </h2>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($project->tags as $tag)
                                    <span class="bg-secondary-accent text-primary-accent text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Placeholder Sections - Need separate API calls for detailed lists --}}
                    {{-- Recent Commits Section --}}
                    <div class="mt-8 pt-6 border-t border-[var(--app-border-color)]">
                        <h2 class="text-xl font-semibold text-[var(--app-text-primary)] mb-4">Recent Commits (Placeholder)</h2>
                        <div class="space-y-3">
                            {{-- Placeholder content remains, actual commit list requires /commits API call --}}
                            @for ($i = 0; $i < 1; $i++)
                                <div class="app-card p-3 rounded-md bg-[var(--app-bg-secondary)]">
                                    <p class="text-sm text-[var(--app-text-primary)]">Data for recent commits will be fetched from the GitHub API.</p>
                                </div>
                            @endfor
                            @if($project->githubData && $project->githubData->html_url)
                            <a href="{{ $project->githubData->html_url }}/commits" target="_blank" rel="noopener noreferrer" class="text-sm text-primary-accent hover:underline block mt-2">View all commits on GitHub &rarr;</a>
                            @endif
                        </div>
                    </div>

                    {{-- Open Issues Section --}}
                    <div class="mt-8 pt-6 border-t border-[var(--app-border-color)]">
                        <h2 class="text-xl font-semibold text-[var(--app-text-primary)] mb-4">
                            Open Issues @if($project->githubData) ({{ $project->githubData->open_issues_count ?? '0' }}) @else (Placeholder) @endif
                        </h2>
                        <div class="space-y-3">
                             {{-- Placeholder content remains, actual issue list requires /issues API call --}}
                            @for ($i = 0; $i < 1; $i++)
                            <div class="app-card p-3 rounded-md bg-[var(--app-bg-secondary)]">
                                <p class="text-sm text-[var(--app-text-primary)]">Data for open issues will be fetched from the GitHub API.</p>
                            </div>
                            @endfor
                            @if($project->githubData && $project->githubData->html_url)
                            <a href="{{ $project->githubData->html_url }}/issues" target="_blank" rel="noopener noreferrer" class="text-sm text-primary-accent hover:underline block mt-2">View all issues on GitHub &rarr;</a>
                            @endif
                        </div>
                    </div>

                    {{-- Pull Requests Section --}}
                    <div class="mt-8 pt-6 border-t border-[var(--app-border-color)]">
                        <h2 class="text-xl font-semibold text-[var(--app-text-primary)] mb-4">Pull Requests (Placeholder)</h2>
                         <div class="space-y-3">
                             {{-- Placeholder content remains, actual PR list requires /pulls API call --}}
                            @for ($i = 0; $i < 1; $i++)
                            <div class="app-card p-3 rounded-md bg-[var(--app-bg-secondary)]">
                                <p class="text-sm text-[var(--app-text-primary)]">Data for pull requests will be fetched from the GitHub API.</p>
                            </div>
                            @endfor
                            @if($project->githubData && $project->githubData->html_url)
                            <a href="{{ $project->githubData->html_url }}/pulls" target="_blank" rel="noopener noreferrer" class="text-sm text-primary-accent hover:underline block mt-2">View all pull requests on GitHub &rarr;</a>
                            @endif
                        </div>
                    </div>

                    {{-- Contributors Section --}}
                     <div class="mt-8 pt-6 border-t border-[var(--app-border-color)]">
                        <h2 class="text-xl font-semibold text-[var(--app-text-primary)] mb-4">Contributors (Placeholder)</h2>
                        <div class="space-y-3">
                            {{-- Placeholder content remains, actual contributor list requires /contributors API call --}}
                            @for ($i = 0; $i < 1; $i++)
                            <div class="app-card p-3 rounded-md bg-[var(--app-bg-secondary)]">
                                <p class="text-sm text-[var(--app-text-primary)]">Data for contributors will be fetched from the GitHub API.</p>
                            </div>
                            @endfor
                            @if($project->githubData && $project->githubData->html_url)
                                <a href="{{ $project->githubData->html_url }}/graphs/contributors" target="_blank" rel="noopener noreferrer" class="text-sm text-primary-accent hover:underline block mt-2">View contributor graph on GitHub &rarr;</a>
                            @endif
                        </div>
                    </div>

                    {{-- Comments Section (Keep your existing comments section and form as is) --}}
                    <div class="mt-10">
                        <h2 class="text-xl font-semibold text-[var(--app-text-primary)] border-b border-[var(--app-border-color)] pb-2 mb-4">
                            Discussion
                        </h2>
                        @forelse ($project->comments as $comment)
                            <div class="app-card p-3 mb-3 rounded-md bg-[var(--app-bg-secondary)]">
                                <p class="text-sm text-[var(--app-text-primary)]">{!! nl2br(e($comment->body)) !!}</p> {{-- Assuming comment content is in 'body' --}}
                                <p class="text-xs text-[var(--app-text-secondary)] mt-1">By
                                    {{ $comment->user->name ?? 'Anonymous' }} -
                                    {{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="text-[var(--app-text-secondary)] text-sm">No comments yet. Be the first to say something!</p>
                        @endforelse

                        @auth
                            <div class="mt-6 pt-4 border-t border-[var(--app-border-color)]">
                                <h3 class="text-lg font-semibold text-[var(--app-text-primary)] mb-3">Leave a Comment</h3>
                                <form action="{{ route('projects.comments.store', $project->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <textarea name="body" id="comment_content" rows="4" {{-- Changed name to 'body' to match common practice and Comment model --}}
                                            class="w-full p-2 border border-[var(--app-border-color)] rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)]"
                                            placeholder="Write your comment here..." required>{{ old('body') }}</textarea>
                                        @error('body')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-primary-accent border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-accent disabled:opacity-25 transition ease-in-out duration-150">
                                            Post Comment
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <p class="mt-6 pt-4 border-t border-[var(--app-border-color)] text-sm text-[var(--app-text-secondary)]">
                                Please <a href="{{ route('login') }}" class="text-primary-accent hover:underline">log in</a> or
                                <a href="{{ route('register') }}" class="text-primary-accent hover:underline">register</a> to post a comment.
                            </p>
                        @endauth
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const languagesChartDiv = document.getElementById('echartsLanguagesChart');

            @php
                $languagesDataForJs = [];
                // Use 'languages_data' which should store the JSON from /languages endpoint
                if (isset($project->githubData) && $project->githubData->languages_data) {
                    $langData = $project->githubData->languages_data;
                    $decodedLangData = is_string($langData) ? json_decode($langData, true) : (is_array($langData) ? $langData : []);

                    if (is_array($decodedLangData) && !empty($decodedLangData)) {
                        $totalBytes = array_sum($decodedLangData); // Sum of all language bytes
                        if ($totalBytes > 0) {
                            foreach ($decodedLangData as $name => $bytes) {
                                if (is_numeric($bytes) && $bytes > 0) {
                                    // ECharts pie chart can take raw values and calculate percentages itself
                                    // Or you can pass pre-calculated percentages if your chart config expects it.
                                    // Here, we pass raw byte values. The tooltip formatter '{d}%' will show percentage.
                                    $languagesDataForJs[] = ['name' => $name, 'value' => floatval($bytes)];
                                }
                            }
                        }
                    }
                }
                $languageColorsForJs = $languageColors ?? []; // Ensure $languageColors is passed from controller
            @endphp

            const echartsLanguagesData = @json($languagesDataForJs);
            const languageColorMap = @json($languageColorsForJs);

            if (languagesChartDiv && echartsLanguagesData.length > 0 && typeof echarts !== 'undefined') {
                const myChart = echarts.init(languagesChartDiv, null, { renderer: 'svg' });

                const seriesData = echartsLanguagesData.map(item => {
                    // Use the languageColorMap passed from the controller
                    let color = languageColorMap[item.name] || languageColorMap[ucfirst(strtolower(item.name))] || '#A0AEC0'; // Default grey

                    // Optional: If you want to apply opacity, do it here or in ECharts options directly
                    // This example doesn't modify color with opacity here, assumes ECharts handles it or colors are solid
                    return {
                        name: item.name,
                        value: item.value,
                        itemStyle: { color: color }
                    };
                });

                const option = {
                    tooltip: {
                        trigger: 'item',
                        formatter: function (params) {
                            // params.percent will give the percentage calculated by ECharts
                            return `${params.seriesName}<br/>${params.name}: ${params.value.toLocaleString()} bytes (${params.percent}%)`;
                        },
                        backgroundColor: 'rgba(31, 41, 55, 0.9)', // dark:bg-gray-800
                        borderColor: 'rgba(55, 65, 81, 0.9)',   // dark:border-gray-700
                        textStyle: { color: '#F3F4F6' }       // dark:text-gray-200
                    },
                    series: [{
                        name: 'Languages',
                        type: 'pie',
                        radius: ['50%', '75%'], // Doughnut
                        avoidLabelOverlap: true,
                        itemStyle: {
                            borderRadius: 5,
                            borderColor: getComputedStyle(document.documentElement).getPropertyValue('--app-bg-secondary').trim(), // Use your app's secondary bg color
                            borderWidth: 2,
                            shadowBlur: 8,
                            shadowOffsetX: 1,
                            shadowOffsetY: 1,
                            shadowColor: 'rgba(0, 0, 0, 0.08)'
                        },
                        label: { show: false }, // Labels on slices
                        emphasis: {
                            focus: 'self',
                            label: { // Label on hover
                                show: true,
                                fontSize: 14,
                                fontWeight: 'bold',
                                formatter: '{b}\n{d}%', // {b} is name, {d} is percentage
                                color: 'var(--app-text-primary)'
                            },
                            itemStyle: {
                                shadowBlur: 15,
                                shadowColor: 'rgba(0,0,0,0.2)'
                            }
                        },
                        data: seriesData,
                    }]
                };
                myChart.setOption(option);
                window.addEventListener('resize', function() { myChart.resize(); });
            } else if (languagesChartDiv) {
                languagesChartDiv.innerHTML = '<p class="text-xs text-center text-[var(--app-text-secondary)] p-4">Language chart data for this repository is not available or not yet processed.</p>';
                languagesChartDiv.style.height = 'auto';
            }
        });
    </script>
@endpush
