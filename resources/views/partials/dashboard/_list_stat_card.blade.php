{{-- resources/views/partials/dashboard/_list_stat_card.blade.php --}}
@props([
    'title' => 'Default Title',
    'items' => []
])

<div class="app-card rounded-lg shadow p-4 md:p-6">
    <h3 class="text-lg font-semibold text-[var(--app-text-primary)] mb-4">{{ $title }}</h3>
    <ul class="space-y-3">
        @forelse ($items as $item)
            <li class="flex items-center justify-between text-sm">
                <div class="flex items-center">
                    @if(isset($item['color']))
                        <span class="mr-2 inline-block h-2.5 w-2.5 rounded-full {{ $item['color'] }}"></span>
                    @endif
                    <span class="text-[var(--app-text-secondary)]">{{ $item['name'] }}</span>
                </div>
                <div class="flex items-center">
                    <span class="font-medium text-[var(--app-text-primary)] mr-2">{{ $item['value'] }}</span>
                    @if(isset($item['percentage']))
                        <span class="text-xs px-1.5 py-0.5 rounded-full
                            @if(Str::startsWith($item['percentage'], '+') || (float)trim($item['percentage'], '%') > 0 && !Str::startsWith($item['percentage'], '-'))
                                bg-green-100 text-green-700 dark:bg-green-700 dark:text-green-100
                            @elseif(Str::startsWith($item['percentage'], '-'))
                                bg-red-100 text-red-700 dark:bg-red-700 dark:text-red-100
                            @else
                                bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-100
                            @endif
                        ">
                            {{ $item['percentage'] }}
                        </span>
                    @endif
                </div>
            </li>
        @empty
            <li class="text-sm text-[var(--app-text-secondary)]">No data available.</li>
        @endforelse
    </ul>
</div>
