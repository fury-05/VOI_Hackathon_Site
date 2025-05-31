{{-- resources/views/partials/dashboard/_small_stat_card.blade.php --}}
@props([
    'title' => 'Default Title',
    'value' => '0',
    'change' => null, // e.g., '+5%', '-10 Users'
    'changeType' => 'neutral', // 'positive', 'negative', 'neutral'
    'iconPath' => null, // Optional SVG path
    'iconBgColor' => 'bg-gray-200 dark:bg-gray-700'
])

<div class="app-card rounded-lg shadow p-4">
    <div class="flex items-center">
        @if($iconPath)
        <div class="flex-shrink-0 mr-3">
            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $iconBgColor }} text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" />
                </svg>
            </span>
        </div>
        @endif
        <div>
            <p class="text-xs text-[var(--app-text-secondary)] uppercase tracking-wider">{{ $title }}</p>
            <p class="text-xl font-bold text-[var(--app-text-primary)]">{{ $value }}</p>
        </div>
    </div>
    @if($change)
        <p class="mt-2 text-xs
            @if($changeType === 'positive') text-green-600 dark:text-green-400 @endif
            @if($changeType === 'negative') text-red-600 dark:text-red-400 @endif
            @if($changeType === 'neutral') text-[var(--app-text-secondary)] @endif
        ">
            {{ $change }}
        </p>
    @endif
</div>
