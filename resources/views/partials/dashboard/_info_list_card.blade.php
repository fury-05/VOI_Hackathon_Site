{{-- resources/views/partials/dashboard/_info_list_card.blade.php --}}
@props([
    'title' => 'Default List Title',
    'items' => [],
    'viewAllUrl' => null,
    'viewAllText' => 'View All'
])

<div class="app-card rounded-lg shadow p-4 md:p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-[var(--app-text-primary)]">{{ $title }}</h3>
        @if($viewAllUrl)
        <a href="{{ $viewAllUrl }}" class="text-sm text-primary-accent hover:underline">{{ $viewAllText }}</a>
        @endif
    </div>
    <ul class="space-y-4">
        @forelse ($items as $item)
            <li class="flex items-center justify-between pb-3 border-b border-[var(--app-border-color)] last:border-b-0 last:pb-0">
                <div class="flex-grow mr-2">
                    <p class="text-sm font-medium text-[var(--app-text-primary)] truncate">{{ $item['title'] ?? 'N/A' }}</p>
                    @if(isset($item['subtitle']))
                        <p class="text-xs text-[var(--app-text-secondary)] truncate">{{ $item['subtitle'] }}</p>
                    @endif
                </div>
                <div class="flex-shrink-0 text-sm">
                    @if(isset($item['value']))
                        @php
                            // Define defaults carefully
                            $valueType = $item['valueType'] ?? 'text';
                            $url = $item['url'] ?? '#';
                            $valueColor = $item['valueColor'] ?? null; // Default to null if not set
                        @endphp

                        @if($valueType === 'link')
                            <a href="{{ $url }}" class="font-medium text-primary-accent hover:underline">
                                {{ $item['value'] }}
                            </a>
                        @elseif($valueType === 'button')
                            @php
                                $buttonTextColor = $valueColor ?: 'text-primary-accent'; // Use $valueColor if set, else default
                                $buttonBorderColor = 'border-primary-accent'; // Default border
                                if ($valueColor && str_starts_with($valueColor, 'text-')) {
                                    $buttonBorderColor = str_replace('text-', 'border-', $valueColor);
                                } elseif ($valueColor && str_starts_with($valueColor, 'border-')) {
                                    $buttonBorderColor = $valueColor;
                                }
                            @endphp
                            <button class="font-medium {{ $buttonTextColor }} hover:opacity-80 text-xs py-1 px-2 rounded border {{ $buttonBorderColor }}">
                                {{ $item['value'] }}
                            </button>
                        @else {{-- Default to 'text' type --}}
                            <span class="font-semibold {{ $valueColor ?: 'text-[var(--app-text-primary)]' }}">
                                {{ $item['value'] }}
                            </span>
                        @endif
                    @endif
                </div>
            </li>
        @empty
            <li class="text-sm text-[var(--app-text-secondary)]">No items to display.</li>
        @endforelse
    </ul>
</div>
