{{-- resources/views/partials/dashboard/_platform_stats_card.blade.php --}}
@props([
    'title' => 'Platform Overview',
    'stats' => [], // Expected: [['label' => 'Label', 'value' => 'Value', 'icon_path' => 'svg-d-attribute', 'icon_bg_color' => 'bg-color-class']]
    'growthText' => null
])

<div class="app-card rounded-lg shadow p-4 md:p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-[var(--app-text-primary)]">{{ $title }}</h3>
        @if($growthText)
        <span class="text-xs font-medium text-green-500">{{ $growthText }}</span>
        @endif
    </div>
    <div class="flex flex-wrap -m-2">
        @forelse ($stats as $stat)
            <div class="w-1/2 sm:w-1/2 lg:w-1/4 p-2">
                {{-- Added title attribute and tabindex="0" for hover tooltip --}}
                <div title="{{ $stat['label'] }}" tabindex="0" class="app-card h-full p-3 rounded-lg bg-[var(--app-bg-secondary)] border border-[var(--app-border-color)] transition-all duration-300 ease-in-out hover:shadow-md hover:scale-105 hover:bg-[var(--app-card-bg)] text-center flex flex-col justify-between focus:outline-none focus:ring-2 focus:ring-primary-accent">
                    <div>
                        <p class="text-xs text-[var(--app-text-secondary)] uppercase tracking-wider mb-1 truncate">{{ $stat['label'] }}</p>

                        <div class="flex items-center justify-center space-x-2">
                            @if(isset($stat['icon_path']))
                            <div class="inline-flex items-center justify-center h-8 w-8 rounded-lg {{ $stat['icon_bg_color'] ?? 'bg-primary-accent' }} text-white flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon_path'] }}" />
                                </svg>
                            </div>
                            @endif
                            <p class="text-xl sm:text-2xl font-bold text-[var(--app-text-primary)]">{{ $stat['value'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="w-full text-sm text-[var(--app-text-secondary)] p-2">No stats available.</p>
        @endforelse
    </div>
</div>
