{{-- resources/views/partials/dashboard/_highlight_card.blade.php --}}
@props([
    'item' => null // Expects an 'item' array with 'type', 'title', 'subtitle', 'description', 'url', 'buttonText'
])

<div class="app-card rounded-lg shadow p-6 relative overflow-hidden">
    @if($item)
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-lg font-semibold text-primary-accent">{{ $item['type'] ?? 'Spotlight!' }}</h2>
                <p class="mt-1 text-2xl font-bold text-[var(--app-text-primary)]">{{ $item['title'] ?? 'Featured Item' }}</p>
                <p class="text-sm text-[var(--app-text-secondary)] mt-1">{{ $item['subtitle'] ?? '' }}</p>
                <p class="text-xs text-[var(--app-text-secondary)] mt-1">{{ $item['description'] ?? '' }}</p>
                <a href="{{ $item['url'] ?? '#' }}" id="confetti-trigger-{{ Str::slug($item['title'] ?? 'default') }}" class="mt-4 inline-block bg-primary-accent text-white text-sm font-medium py-2 px-4 rounded-md hover:opacity-90 transition-opacity">
                    {{ $item['buttonText'] ?? 'View Details' }}
                </a>
            </div>
            <div class="text-6xl text-yellow-400 flex-shrink-0 ml-4">
                @if(($item['type'] ?? '') === 'Project Winner')
                    🏆
                @elseif(($item['type'] ?? '') === 'Active Hackathon')
                    🎉
                @else
                    🌟
                @endif
            </div>
        </div>
    @else
        <p class="text-[var(--app-text-primary)]">No highlight available at the moment.</p>
    @endif
</div>

@if($item)
@push('scripts')
<script>
    // document.addEventListener('DOMContentLoaded', function() {
    //     const triggerButton = document.getElementById('confetti-trigger-{{ Str::slug($item['title'] ?? 'default') }}');
    //     if (triggerButton && typeof confetti === 'function') {
    //         // Example: Trigger confetti on button click
    //         // triggerButton.addEventListener('click', () => {
    //         //     confetti({ particleCount: 100, spread: 70, origin: { y: 0.6 } });
    //         // });

    //         // Or, trigger confetti on load for this card if it's a "winner"
    //         if ("{{ $item['type'] ?? '' }}" === "Project Winner") {
    //              confetti({ particleCount: 120, spread: 80, origin: { y: 0.5, x: 0.5 }, zIndex: 1050 });
    //         }
    //     }
    // });
</script>
@endpush
@endif
