    {{-- resources/views/partials/dashboard/_github_activity_snapshot_card.blade.php --}}
    @props([
        'title' => 'GitHub Activity Snapshot',
        'period' => 'Today', // e.g., Today, This Week
        'stats' => [], // Expected: [['label' => 'Label', 'value' => 'Value', 'icon_path' => 'svg-d-attribute', 'icon_bg_color' => 'bg-color-class']]
    ])

    <div class="app-card rounded-lg shadow p-4 md:p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-[var(--app-text-primary)]">{{ $title }}</h3>
            @if($period)
            <span class="text-xs font-medium text-[var(--app-text-secondary)]">{{ $period }}</span>
            @endif
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 gap-4 text-center md:text-left"> {{-- Adjusted to 2 columns for this card type --}}
            @forelse ($stats as $stat)
                <div class="p-3 rounded-lg bg-[var(--app-bg-secondary)] border border-[var(--app-border-color)]">
                    <div class="flex flex-col items-center md:flex-row md:items-center">
                        @if(isset($stat['icon_path']))
                        <div class="mb-2 md:mb-0 md:mr-3 inline-flex items-center justify-center h-10 w-10 rounded-lg {{ $stat['icon_bg_color'] ?? 'bg-primary-accent' }} text-white flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon_path'] }}" />
                            </svg>
                        </div>
                        @endif
                        <div class="min-w-0"> {{-- Added min-w-0 for better truncation if needed --}}
                            <p class="text-xs text-[var(--app-text-secondary)] uppercase tracking-wider truncate">{{ $stat['label'] }}</p>
                            <p class="text-xl md:text-2xl font-bold text-[var(--app-text-primary)] truncate">{{ $stat['value'] }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-sm text-[var(--app-text-secondary)]">No activity data available.</p>
            @endforelse
        </div>
    </div>
