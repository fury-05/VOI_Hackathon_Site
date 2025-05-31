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
                            @if ($project->team)
                                <span>| Team: {{ $project->team->name }}</span>
                            @endif
                            @if ($project->hackathon)
                                <span class="block sm:inline mt-1 sm:mt-0 sm:ml-2">| Submitted to:
                                    <a href="#"
                                        class="text-primary-accent hover:underline">{{ $project->hackathon->name }}</a>
                                </span>
                            @endif
                        </div>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @if ($project->github_repo_url)
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

                    {{-- Project Description --}}
                    <div
                        class="prose prose-sm sm:prose-base dark:prose-invert max-w-none mb-8 text-[var(--app-text-primary)]">
                        <h2
                            class="text-xl font-semibold text-[var(--app-text-primary)] border-b border-[var(--app-border-color)] pb-2 mb-3">
                            Description</h2>
                        {!! nl2br(e($project->description)) !!}
                    </div>

                    {{-- GitHub Stats Section --}}
                    @if ($project->githubData)
                        <div class="mb-8">
                            <h2
                                class="text-xl font-semibold text-[var(--app-text-primary)] border-b border-[var(--app-border-color)] pb-2 mb-4">
                                GitHub Statistics</h2>
                            {{-- New Structure for GitHub Stats --}}
                            <div class="space-y-6">
                                {{-- Row 1: Core Repo Stats --}}
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                    <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                        <div class="flex items-center text-primary-accent">
                                            {{-- Heroicon: star --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                                class="w-5 h-5 mr-2">
                                                <path fill-rule="evenodd"
                                                    d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.116 3.986 1.241 5.375c.216 1.004-.97 1.758-1.86 1.214l-4.796-2.796-4.796 2.796c-.89.544-2.076-.21-1.86-1.214l1.241-5.375L1.646 11.049c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm font-medium text-[var(--app-text-secondary)]">Stars</span>
                                        </div>
                                        <p class="text-2xl font-bold text-[var(--app-text-primary)] mt-1">
                                            {{ $project->githubData->stars_count ?? 'N/A' }}</p>
                                    </div>
                                    <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                        <div class="flex items-center text-primary-accent">
                                            {{-- Heroicon: arrow-trending-up (for forks as a sign of derivative work/interest) --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                                class="w-5 h-5 mr-2">
                                                <path fill-rule="evenodd"
                                                    d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm.092 9.44a.75.75 0 00-1.061 0 3.751 3.751 0 00-1.06 1.061.75.75 0 101.273.836 2.25 2.25 0 01.472-.472.75.75 0 00-.212-1.425zM12 7.5a.75.75 0 01.75.75v3.546l2.472 2.472a.75.75 0 11-1.06 1.06l-2.75-2.75a.75.75 0 010-1.061V8.25A.75.75 0 0112 7.5z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm font-medium text-[var(--app-text-secondary)]">Forks</span>
                                        </div>
                                        <p class="text-2xl font-bold text-[var(--app-text-primary)] mt-1">
                                            {{ $project->githubData->forks_count ?? 'N/A' }}</p>
                                    </div>
                                    <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                        <div class="flex items-center text-primary-accent">
                                            {{-- Heroicon: eye --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                                class="w-5 h-5 mr-2">
                                                <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                                <path fill-rule="evenodd"
                                                    d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span
                                                class="text-sm font-medium text-[var(--app-text-secondary)]">Watchers</span>
                                        </div>
                                        <p class="text-2xl font-bold text-[var(--app-text-primary)] mt-1">
                                            {{ $project->githubData->watchers_count ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                {{-- Row 2: Activity Stats --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                        <p class="text-sm font-medium text-[var(--app-text-secondary)]">Total Commits</p>
                                        <p class="text-2xl font-bold text-[var(--app-text-primary)] mt-1">
                                            {{ $project->githubData->commits_count ?? 'N/A' }}</p>
                                        @if ($project->githubData->last_commit_at)
                                            <p class="text-xs text-[var(--app-text-secondary)] mt-1">Last:
                                                {{ $project->githubData->last_commit_at->format('M d, Y') }}</p>
                                        @endif
                                    </div>
                                    <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                        <p class="text-sm font-medium text-[var(--app-text-secondary)]">Contributors</p>
                                        <p class="text-2xl font-bold text-[var(--app-text-primary)] mt-1">
                                            {{ $project->githubData->contributors_count ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                {{-- Row 3: Issues and PRs --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                        <p class="text-sm font-medium text-[var(--app-text-secondary)]">Issues</p>
                                        <p class="text-lg font-bold text-[var(--app-text-primary)] mt-1">
                                            {{ $project->githubData->open_issues_count ?? '0' }} <span
                                                class="text-sm font-normal text-green-500">Open</span> /
                                            {{ $project->githubData->issues_count ?? '0' }} <span
                                                class="text-sm font-normal text-[var(--app-text-secondary)]">Total</span>
                                        </p>
                                    </div>
                                    <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                        <p class="text-sm font-medium text-[var(--app-text-secondary)]">Pull Requests</p>
                                        <p class="text-lg font-bold text-[var(--app-text-primary)] mt-1">
                                            {{ $project->githubData->open_pull_requests_count ?? '0' }} <span
                                                class="text-sm font-normal text-green-500">Open</span> /
                                            {{ $project->githubData->pull_requests_count ?? '0' }} <span
                                                class="text-sm font-normal text-[var(--app-text-secondary)]">Total</span>
                                        </p>
                                        <p class="text-xs text-blue-500 mt-1">
                                            {{ $project->githubData->merged_pull_requests_count ?? '0' }} Merged</p>
                                    </div>
                                </div>

                                {{-- Languages Section with ECharts --}}

                                {{-- Languages Section with ECharts (Reverted to a more stable layout) --}}
                                @if ($project->githubData && $project->githubData->languages)
                                    @php
                                        // Ensure $languagesArray is an associative array
                                        $languagesDataString = $project->githubData->languages;
                                        $languagesArray = is_string($languagesDataString)
                                            ? json_decode($languagesDataString, true)
                                            : (is_array($languagesDataString)
                                                ? $languagesDataString
                                                : []);
                                    @endphp

                                    @if (is_array($languagesArray) && !empty($languagesArray))
                                        <div class="app-card p-4 rounded-lg bg-[var(--app-bg-secondary)]">
                                            <h4 class="text-sm font-medium text-[var(--app-text-secondary)] mb-3">Languages
                                                Used</h4>
                                            {{-- Flex container for chart and legend --}}
                                            <div class="flex flex-col md:flex-row items-center md:items-start md:space-x-4">
                                                {{-- ECharts container --}}
                                                <div id="echartsLanguagesChart" style="width: 160px; height: 160px;"
                                                    class="flex-shrink-0 mx-auto md:mx-0"></div>

                                                {{-- Legend/List --}}
                                                <ul
                                                    class="list-none p-0 m-0 mt-4 md:mt-0 flex-grow w-full md:w-auto space-y-1">
                                                    @foreach ($languagesArray as $lang => $percentage)
                                                        @php
                                                            // Ensure $languageColors is available from your controller
                                                            $colorValue = $languageColors[$lang] ?? '#cccccc';
                                                        @endphp
                                                        <li class="text-xs flex items-center py-0.5">
                                                            <span
                                                                class="inline-block w-2.5 h-2.5 rounded-full mr-2 flex-shrink-0"
                                                                style="background-color: {{ $colorValue }};"></span>
                                                            <span
                                                                class="text-[var(--app-text-secondary)] mr-1">{{ $lang }}:</span>
                                                            <span
                                                                class="font-medium text-[var(--app-text-primary)]">{{ number_format($percentage, 1) }}%</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        {{-- The ECharts script for this section will be pushed to the stack --}}
                                    @else
                                        <p class="text-xs text-[var(--app-text-secondary)]">Language data could not be
                                            processed or is empty.</p>
                                    @endif
                                @else
                                    <p class="text-xs text-[var(--app-text-secondary)]">Language data not available.</p>
                                @endif
                                {{-- End of Languages Section --}}



                                {{-- --------------------------------------- --}}

                            </div>
                            <p class="mt-6 text-xs text-[var(--app-text-secondary)] text-right">Stats last fetched:
                                {{ $project->githubData->last_fetched_at ? $project->githubData->last_fetched_at->diffForHumans() : 'N/A' }}
                            </p>
                        </div>
                    @else
                        <p class="text-[var(--app-text-secondary)]">GitHub data not available for this project yet.</p>
                    @endif

                    {{-- Tags Section --}}
                    @if ($project->tags && count($project->tags) > 0)
                        <div class="mb-8">
                            <h2
                                class="text-xl font-semibold text-[var(--app-text-primary)] border-b border-[var(--app-border-color)] pb-2 mb-3">
                                Technologies / Tags</h2>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($project->tags as $tag)
                                    <span
                                        class="bg-secondary-accent text-primary-accent text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    {{-- ------------------------------------------------- --}}

                    {{-- Tags Section ... (Keep this as is) --}}


                    {{-- NEW: Recent Commits Section (Placeholder) --}}
                    <div class="mt-8 pt-6 border-t border-[var(--app-border-color)]">
                        <h2 class="text-xl font-semibold text-[var(--app-text-primary)] mb-4">Recent Commits (Placeholder)
                        </h2>
                        <div class="space-y-3">
                            @for ($i = 0; $i < 3; $i++)
                                <div class="app-card p-3 rounded-md bg-[var(--app-bg-secondary)]">
                                    <p class="text-sm font-medium text-[var(--app-text-primary)]">
                                        feat: Implement user authentication flow <span
                                            class="text-xs text-[var(--app-text-secondary)]">(#{{ rand(100, 200) }})</span>
                                    </p>
                                    <p class="text-xs text-[var(--app-text-secondary)] mt-1">
                                        Committed by <span class="font-semibold text-[var(--app-text-primary)]">User
                                            {{ $i + 1 }}</span> -
                                        {{ now()->subDays($i * 2)->subHours($i * 3)->diffForHumans() }}
                                    </p>
                                </div>
                            @endfor
                            <a href="#" class="text-sm text-primary-accent hover:underline block mt-2">View all
                                commits on GitHub &rarr;</a>
                        </div>
                    </div>

                    {{-- NEW: Open Issues Section (Placeholder) --}}
                    <div class="mt-8 pt-6 border-t border-[var(--app-border-color)]">
                        <h2 class="text-xl font-semibold text-[var(--app-text-primary)] mb-4">Open Issues (Placeholder)
                        </h2>
                        <div class="space-y-3">
                            @for ($i = 0; $i < 2; $i++)
                                <div class="app-card p-3 rounded-md bg-[var(--app-bg-secondary)]">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-medium text-[var(--app-text-primary)]">
                                                Bug: Login button unresponsive on mobile <span
                                                    class="text-xs text-[var(--app-text-secondary)]">#{{ rand(10, 50) }}</span>
                                            </p>
                                            <p class="text-xs text-[var(--app-text-secondary)] mt-1">
                                                Opened {{ now()->subDays($i + 5)->diffForHumans() }} by <span
                                                    class="font-semibold text-[var(--app-text-primary)]">Reporter
                                                    {{ $i + 1 }}</span>
                                            </p>
                                        </div>
                                        <span
                                            class="text-xs bg-red-100 text-red-700 dark:bg-red-700 dark:text-red-100 px-2 py-0.5 rounded-full">Open</span>
                                    </div>
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        <span class="text-xs bg-gray-200 dark:bg-gray-600 px-1.5 py-0.5 rounded">bug</span>
                                        <span
                                            class="text-xs bg-gray-200 dark:bg-gray-600 px-1.5 py-0.5 rounded">mobile</span>
                                        <span class="text-xs bg-gray-200 dark:bg-gray-600 px-1.5 py-0.5 rounded">UI</span>
                                    </div>
                                </div>
                            @endfor
                            <a href="#" class="text-sm text-primary-accent hover:underline block mt-2">View all
                                issues on GitHub &rarr;</a>
                        </div>
                    </div>

                    {{-- NEW: Pull Requests Section (Placeholder) --}}
                    <div class="mt-8 pt-6 border-t border-[var(--app-border-color)]">
                        <h2 class="text-xl font-semibold text-[var(--app-text-primary)] mb-4">Pull Requests (Placeholder)
                        </h2>
                        <div class="space-y-3">
                            @for ($i = 0; $i < 2; $i++)
                                <div class="app-card p-3 rounded-md bg-[var(--app-bg-secondary)]">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-medium text-[var(--app-text-primary)]">
                                                Fix: Resolve N+1 query in user profile <span
                                                    class="text-xs text-[var(--app-text-secondary)]">#{{ rand(60, 90) }}</span>
                                            </p>
                                            <p class="text-xs text-[var(--app-text-secondary)] mt-1">
                                                Opened {{ now()->subDays($i + 1)->diffForHumans() }} by <span
                                                    class="font-semibold text-[var(--app-text-primary)]">Contributor
                                                    {{ $i + 1 }}</span>
                                            </p>
                                        </div>
                                        <span
                                            class="text-xs bg-green-100 text-green-700 dark:bg-green-700 dark:text-green-100 px-2 py-0.5 rounded-full">Open</span>
                                    </div>
                                    <p class="text-xs text-[var(--app-text-secondary)] mt-1">Target: main &larr; Source:
                                        feature/profile-optimization</p>
                                </div>
                            @endfor
                            <a href="#" class="text-sm text-primary-accent hover:underline block mt-2">View all pull
                                requests on GitHub &rarr;</a>
                        </div>
                    </div>

                    {{-- ----------------------------------------------- --}}

                    {{-- NEW: Contributors Section (Placeholder) --}}
                    <div class="mt-8 pt-6 border-t border-[var(--app-border-color)]">
                        <h2 class="text-xl font-semibold text-[var(--app-text-primary)] mb-4">Contributors (Placeholder)
                        </h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @php
                                $contributors = [
                                    [
                                        'name' => 'Alice Wonder',
                                        'avatar_initial' => 'AW',
                                        'contributions' => rand(50, 150),
                                        'github_url' => '#',
                                    ],
                                    [
                                        'name' => 'Bob The Builder',
                                        'avatar_initial' => 'BB',
                                        'contributions' => rand(30, 100),
                                        'github_url' => '#',
                                    ],
                                    [
                                        'name' => 'Charlie Coder',
                                        'avatar_initial' => 'CC',
                                        'contributions' => rand(70, 200),
                                        'github_url' => '#',
                                    ],
                                    [
                                        'name' => 'Diana Designer',
                                        'avatar_initial' => 'DD',
                                        'contributions' => rand(20, 80),
                                        'github_url' => '#',
                                    ],
                                    [
                                        'name' => 'Edward Engineer',
                                        'avatar_initial' => 'EE',
                                        'contributions' => rand(40, 120),
                                        'github_url' => '#',
                                    ],
                                ];
                            @endphp

                            @foreach ($contributors as $contributor)
                                <a href="{{ $contributor['github_url'] }}" target="_blank" rel="noopener noreferrer"
                                    class="block group text-center app-card p-3 rounded-lg bg-[var(--app-bg-secondary)] hover:shadow-lg transition-shadow">
                                    <div
                                        class="w-16 h-16 mx-auto rounded-full bg-primary-accent text-white flex items-center justify-center text-xl font-semibold mb-2 group-hover:opacity-80">
                                        {{-- In a real scenario, this would be an <img> tag for avatars --}}
                                        {{ $contributor['avatar_initial'] }}
                                    </div>
                                    <p
                                        class="text-sm font-medium text-[var(--app-text-primary)] truncate group-hover:text-primary-accent">
                                        {{ $contributor['name'] }}</p>
                                    <p class="text-xs text-[var(--app-text-secondary)]">
                                        {{ $contributor['contributions'] }} contributions</p>
                                </a>
                            @endforeach
                            {{-- You could add a "View all contributors on GitHub" link if many --}}
                        </div>
                    </div>


                    {{-- Comments Section Placeholder --}}
                    <div class="mt-10">
                        <h2
                            class="text-xl font-semibold text-[var(--app-text-primary)] border-b border-[var(--app-border-color)] pb-2 mb-4">
                            Discussion</h2>
                        @forelse ($project->comments as $comment)
                            <div class="app-card p-3 mb-3 rounded-md bg-[var(--app-bg-secondary)]">
                                <p class="text-sm text-[var(--app-text-primary)]">{{ $comment->content }}</p>
                                <p class="text-xs text-[var(--app-text-secondary)] mt-1">By
                                    {{ $comment->user->name ?? 'Anonymous' }} -
                                    {{ $comment->created_at->diffForHumans() }}</p>
                                {{-- TODO: Add replies and comment form --}}
                            </div>
                        @empty
                            <p class="text-[var(--app-text-secondary)] text-sm">No comments yet. Be the first to say
                                something!</p>
                        @endforelse
                        {{-- TODO: Add a form to submit new comments if user is logged in --}}

                        {{-- NEW: Comment Submission Form (for authenticated users) --}}
                        @auth
                            <div class="mt-6 pt-4 border-t border-[var(--app-border-color)]">
                                <h3 class="text-lg font-semibold text-[var(--app-text-primary)] mb-3">Leave a Comment</h3>
                                <form action="{{ route('projects.comments.store', $project->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <textarea name="content" id="comment_content" rows="4"
                                            class="w-full p-2 border border-[var(--app-border-color)] rounded-md shadow-sm focus:ring-primary-accent focus:border-primary-accent bg-[var(--app-bg-primary)] text-[var(--app-text-primary)]"
                                            placeholder="Write your comment here..." required></textarea>
                                        @error('content')
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
                        {{-- End of NEW Comment Submission Form --}}

                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- Add Font Awesome if you use its classes like fab fa-github / fas fa-external-link-alt --}}
    {{-- You can add this to your layouts.app.blade.php <head> section --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" /> --}}
@endsection
{{-- ----------------------- --}}

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const languagesChartDiv = document.getElementById('echartsLanguagesChart');

            @php
                $languagesDataForJs = [];
                if (isset($project->githubData) && $project->githubData->languages) {
                    $langData = $project->githubData->languages;
                    // Ensure it's decoded to an array if it's a string
                    $decodedLangData = is_string($langData) ? json_decode($langData, true) : (is_array($langData) ? $langData : []);
                    if (is_array($decodedLangData) && !empty($decodedLangData)) {
                        foreach ($decodedLangData as $name => $value) {
                            // Ensure value is a number for ECharts
                            $numericValue = is_numeric($value) ? floatval($value) : 0;
                            $languagesDataForJs[] = ['name' => $name, 'value' => $numericValue];
                        }
                    }
                }
                // Ensure $languageColors is passed from your controller to the view
                $languageColorsForJs = $languageColors ?? [];
            @endphp

            const echartsLanguagesData = @json($languagesDataForJs);
            const languageColorMap = @json($languageColorsForJs);

            if (languagesChartDiv && echartsLanguagesData.length > 0 && typeof echarts !== 'undefined') {
                const myChart = echarts.init(languagesChartDiv, null, {
                    renderer: 'svg'
                });

                const seriesData = echartsLanguagesData.map(item => {
                    let color = languageColorMap[item.name] ||
                    '#cccccc'; // Use map from controller or default
                    const lowerLangName = item.name.toLowerCase();

                    // Fallback color logic if not in map (can be removed if controller map is comprehensive)
                    if (color === '#cccccc') {
                        if (lowerLangName.includes('javascript') || lowerLangName.includes('js')) color =
                            '#f1e05a';
                        else if (lowerLangName.includes('php')) color = '#4F5D95';
                        else if (lowerLangName.includes('html')) color = '#e34c26';
                        else if (lowerLangName.includes('css')) color = '#563d7c';
                        else if (lowerLangName.includes('python')) color = '#3572A5';
                        else if (lowerLangName.includes('java')) color = '#b07219';
                        else if (lowerLangName.includes('typescript')) color = '#2b7489';
                        else if (lowerLangName.includes('ruby')) color = '#701516';
                    }

                    // Apply transparency
                    const hexToRgba = (hex, alpha = 0.85) => {
                        if (!hex || !hex.startsWith('#'))
                    return hex; // Return original if not a valid hex
                        const r = parseInt(hex.slice(1, 3), 16);
                        const g = parseInt(hex.slice(3, 5), 16);
                        const b = parseInt(hex.slice(5, 7), 16);
                        if (isNaN(r) || isNaN(g) || isNaN(b))
                    return hex; // Return original if parsing fails
                        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
                    };
                    const finalColor = color.startsWith('#') ? hexToRgba(color) : color;

                    return {
                        name: item.name,
                        value: item.value,
                        itemStyle: {
                            color: finalColor
                        }
                    };
                });

                const option = {
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b}: {c}% ({d}%)',
                        backgroundColor: 'rgba(31, 41, 55, 0.9)',
                        borderColor: 'rgba(55, 65, 81, 0.9)',
                        textStyle: {
                            color: '#F3F4F6'
                        }
                    },
                    series: [{
                        name: 'Languages',
                        type: 'pie',
                        radius: ['50%', '75%'], // Doughnut
                        avoidLabelOverlap: true,
                        itemStyle: {
                            borderRadius: 5,
                            borderColor: getComputedStyle(document.documentElement).getPropertyValue(
                                '--app-bg-secondary').trim(),
                            borderWidth: 2, // Reduced border width for a cleaner look
                            shadowBlur: 8,
                            shadowOffsetX: 1, // Subtle shadow
                            shadowOffsetY: 1,
                            shadowColor: 'rgba(0, 0, 0, 0.08)'
                        },
                        label: {
                            show: false
                        },
                        emphasis: {
                            focus: 'self',
                            label: {
                                show: true,
                                fontSize: 14, // ECharts uses numbers
                                fontWeight: 'bold',
                                formatter: '{b}\n{d}%',
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

                window.addEventListener('resize', function() {
                    myChart.resize();
                });
            } else if (languagesChartDiv) {
                // Fallback if no data or ECharts not loaded
                languagesChartDiv.innerHTML =
                    '<p class="text-xs text-center text-[var(--app-text-secondary)] p-4">Language chart data unavailable.</p>';
                languagesChartDiv.style.height = 'auto'; // Adjust height if no chart
            }
        });
    </script>
@endpush
