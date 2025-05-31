    {{-- resources/views/partials/dashboard/_top_github_contributors_card.blade.php --}}
    @props([
        'title' => 'Top GitHub Contributors',
        'period' => 'This Week (Dummy)',
        'contributors' => [], // Expected: [['name' => '', 'avatar_initial' => '', 'metric_value' => '', 'metric_label' => 'commits', 'profile_url' => '#']]
        'viewAllUrl' => null,
        'viewAllText' => 'View All Contributors'
    ])

    <div class="app-card rounded-lg shadow p-4 md:p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-[var(--app-text-primary)]">{{ $title }}</h3>
                @if($period)
                <p class="text-xs text-[var(--app-text-secondary)]">{{ $period }}</p>
                @endif
            </div>
            @if($viewAllUrl)
            <a href="{{ $viewAllUrl }}" class="text-sm text-primary-accent hover:underline flex-shrink-0">{{ $viewAllText }}</a>
            @endif
        </div>
        <ul class="space-y-3">
            @forelse ($contributors as $contributor)
                <li class="flex items-center space-x-3 pb-3 border-b border-[var(--app-border-color)] last:border-b-0 last:pb-0">
                    <a href="{{ $contributor['profile_url'] ?? '#' }}" class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-primary-accent text-white flex items-center justify-center text-sm font-semibold hover:opacity-90">
                            {{-- Placeholder for avatar - could be <img> or initials --}}
                            {{ $contributor['avatar_initial'] ?? 'N/A' }}
                        </div>
                    </a>
                    <div class="flex-grow min-w-0">
                        <a href="{{ $contributor['profile_url'] ?? '#' }}" class="text-sm font-medium text-[var(--app-text-primary)] hover:text-primary-accent truncate block">{{ $contributor['name'] ?? 'Contributor Name' }}</a>
                        <p class="text-xs text-[var(--app-text-secondary)]">
                            <span class="font-semibold">{{ $contributor['metric_value'] ?? '0' }}</span> {{ $contributor['metric_label'] ?? 'contributions' }}
                        </p>
                    </div>
                    {{-- Optional: Small action like "View Profile" button --}}
                    {{-- <a href="{{ $contributor['profile_url'] ?? '#' }}" class="text-xs bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-[var(--app-text-secondary)] hover:text-[var(--app-text-primary)] px-2 py-1 rounded-md flex-shrink-0">
                        Profile
                    </a> --}}
                </li>
            @empty
                <li class="text-sm text-[var(--app-text-secondary)]">No contributor data available yet.</li>
            @endforelse
        </ul>
    </div>
