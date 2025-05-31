{{-- resources/views/partials/dashboard/_detailed_list_stat_card.blade.php --}}
@props([
    'title' => 'Default Title',
    'mainValue' => '0',
    'comparisonText' => '',
    'items' => [], // Expected: [['name' => '', 'details' => '', 'value' => '', 'icon' => 'icon-name or svg path']]
])

<div class="app-card rounded-lg shadow p-4 md:p-6">
    <div class="mb-3">
        <h3 class="text-lg font-semibold text-[var(--app-text-primary)] mb-1">{{ $title }}</h3>
        <p class="text-3xl font-bold text-primary-accent">{{ $mainValue }}</p>
        @if($comparisonText)
            <p class="text-xs text-green-500">{{ $comparisonText }}</p>
        @endif
    </div>
    <ul class="space-y-4">
        @forelse ($items as $item)
            <li class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    @if(isset($item['icon']))
                        {{-- Basic icon placeholder - replace with actual SVGs or a robust icon component system --}}
                        @if($item['icon'] === 'rocket')
                            <span class="flex items-center justify-center h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-700 text-purple-600 dark:text-purple-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.812a1.875 1.875 0 011.875 1.875v4.812a6 6 0 005.84-7.38z" /></svg>
                            </span>
                        @elseif($item['icon'] === 'leaf')
                            <span class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100 dark:bg-green-700 text-green-600 dark:text-green-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.16.36a11.24 11.24 0 015.68 0 1.5 1.5 0 01.72 1.32V3.2c0 .83-.8 1.5-1.76 1.5H9.2a1.76 1.76 0 01-1.76-1.5V1.68a1.5 1.5 0 01.72-1.32zM20.04 6.36a1.5 1.5 0 01-1.32.72h-2.86c-.96 0-1.76.67-1.76 1.5v3.2a1.76 1.76 0 001.76 1.76h2.86a1.5 1.5 0 011.32.72l.8 1.61a1.5 1.5 0 010 1.44l-.8 1.6a1.5 1.5 0 01-1.32.72h-2.86a1.76 1.76 0 00-1.76 1.76v3.2c0 .83.8 1.5 1.76 1.5h2.86a1.5 1.5 0 011.32.72l.8 1.6a1.5 1.5 0 010 1.44l-.8 1.61a1.5 1.5 0 01-1.32.72H16c-.96 0-1.76.67-1.76 1.5v1.52a1.5 1.5 0 01-.72 1.32 11.24 11.24 0 01-5.68 0 1.5 1.5 0 01-.72-1.32V20.8c0-.83.8-1.5 1.76-1.5h2.86a1.76 1.76 0 001.76-1.76v-3.2a1.76 1.76 0 00-1.76-1.76H6.36a1.5 1.5 0 01-1.32-.72l-.8-1.6a1.5 1.5 0 010-1.44l.8-1.61a1.5 1.5 0 011.32-.72h2.86c.96 0 1.76-.67 1.76-1.5v-3.2A1.76 1.76 0 009.2 5.26H6.36a1.5 1.5 0 01-1.32-.72l-.8-1.6a1.5 1.5 0 010-1.44l.8-1.6A1.5 1.5 0 016.36 0H8c.96 0 1.76.67 1.76 1.5v1.52a1.5 1.5 0 01.72 1.32 11.24 11.24 0 015.68 0 1.5 1.5 0 01.72 1.32l-.12 1.68z" /></svg>
                            </span>
                        @elseif($item['icon'] === 'chart-bar')
                            <span class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-700 text-blue-600 dark:text-blue-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 019.75 19.875V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                            </span>
                        @else
                            <span class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-200">?</span>
                        @endif
                    @endif
                </div>
                <div class="flex-grow">
                    <p class="font-medium text-[var(--app-text-primary)]">{{ $item['name'] }}</p>
                    <p class="text-xs text-[var(--app-text-secondary)]">{{ $item['details'] }}</p>
                </div>
                <div class="text-sm font-semibold text-[var(--app-text-primary)]">
                    {{ $item['value'] }}
                </div>
            </li>
        @empty
            <li class="text-sm text-[var(--app-text-secondary)]">No items to display.</li>
        @endforelse
    </ul>
</div>
