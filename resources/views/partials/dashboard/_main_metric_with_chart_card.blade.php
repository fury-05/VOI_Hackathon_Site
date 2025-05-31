@props([
    'title' => 'Default Title',
    'value' => '0',
    'chartId' => 'defaultChartId' . rand(1000,9999),
    'chartData' => null
])

<div class="app-card rounded-lg shadow p-4 md:p-6">
    <div class="mb-3">
        <h3 class="text-sm font-medium text-[var(--app-text-secondary)] uppercase tracking-wider">{{ $title }}</h3>
        <p class="text-3xl font-bold text-primary-accent mt-1">{{ $value }}</p>
    </div>
    <div class="h-32 md:h-40">
        <canvas id="{{ $chartId }}"></canvas>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartId_{{ $chartId }} = '{{ $chartId }}';
        const chartCanvas_{{ $chartId }} = document.getElementById(chartId_{{ $chartId }});
        const chartDataFromBlade_{{ $chartId }} = @json($chartData ?? null);

        // Helper function to resolve CSS variable strings or use direct colors
        function resolveChartColor(colorString, fallbackVarName, alpha = null) {
            let resolvedColor = fallbackVarName ? getComputedStyle(document.documentElement).getPropertyValue(fallbackVarName).trim() : 'rgba(0,0,0,0.1)'; // Default fallback
            if (colorString) {
                if (colorString.startsWith('var(')) {
                    const varNameMatch = colorString.match(/--[a-zA-Z0-9-]+/);
                    if (varNameMatch && varNameMatch[0]) {
                        resolvedColor = getComputedStyle(document.documentElement).getPropertyValue(varNameMatch[0]).trim();
                    }
                } else {
                    resolvedColor = colorString; // Assume it's already a valid color (hex, rgb, etc.)
                }
            }
            if (alpha !== null && resolvedColor.startsWith('#')) { // Convert hex to rgba for alpha
                const r = parseInt(resolvedColor.slice(1, 3), 16);
                const g = parseInt(resolvedColor.slice(3, 5), 16);
                const b = parseInt(resolvedColor.slice(5, 7), 16);
                return `rgba(${r}, ${g}, ${b}, ${alpha})`;
            } else if (alpha !== null && resolvedColor.startsWith('rgb(')) { // Add alpha to rgb
                 return resolvedColor.replace('rgb(', 'rgba(').replace(')', `, ${alpha})`);
            }
            return resolvedColor;
        }

        if (chartCanvas_{{ $chartId }} &&
            chartDataFromBlade_{{ $chartId }} &&
            typeof chartDataFromBlade_{{ $chartId }}.labels !== 'undefined' &&
            typeof chartDataFromBlade_{{ $chartId }}.datasets !== 'undefined' &&
            Array.isArray(chartDataFromBlade_{{ $chartId }}.labels) &&
            Array.isArray(chartDataFromBlade_{{ $chartId }}.datasets) &&
            typeof Chart !== 'undefined') {

            const ctx_{{ $chartId }} = chartCanvas_{{ $chartId }}.getContext('2d');
            new Chart(ctx_{{ $chartId }}, {
                data: {
                    labels: chartDataFromBlade_{{ $chartId }}.labels,
                    datasets: chartDataFromBlade_{{ $chartId }}.datasets.map(dataset => ({
                        ...dataset,
                        borderColor: resolveChartColor(dataset.borderColor, '--app-primary-accent'),
                        backgroundColor: resolveChartColor(dataset.backgroundColor, '--app-primary-accent', dataset.type === 'line' ? null : 0.33), // Apply alpha for non-line, or use as is for line
                        tension: dataset.tension === undefined ? 0.3 : dataset.tension,
                        fill: dataset.fill === undefined ? (dataset.type === 'line' ? false : true) : dataset.fill, // Line charts usually no fill by default
                    }))
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { display: (chartDataFromBlade_{{ $chartId }}.datasets.length > 1) },
                        tooltip: {
                            enabled: true,
                            backgroundColor: 'rgba(31, 41, 55, 0.9)', titleColor: '#F3F4F6', bodyColor: '#F3F4F6',
                            borderColor: 'rgba(55, 65, 81, 0.9)', borderWidth: 1
                        }
                    },
                    scales: {
                        y: { display: false, beginAtZero: true },
                        x: { display: false }
                    }
                }
            });
        } else if (chartCanvas_{{ $chartId }}) {
            chartCanvas_{{ $chartId }}.parentElement.style.display = 'flex';
            chartCanvas_{{ $chartId }}.parentElement.style.alignItems = 'center';
            chartCanvas_{{ $chartId }}.parentElement.style.justifyContent = 'center';
            chartCanvas_{{ $chartId }}.parentElement.innerHTML = `<span class="text-xs text-[var(--app-text-secondary)]">(Chart for {{ $title }} will appear here)</span>`;
        }
    });
</script>
@endpush
