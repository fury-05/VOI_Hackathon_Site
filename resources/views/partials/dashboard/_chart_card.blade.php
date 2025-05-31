    {{-- resources/views/partials/dashboard/_chart_card.blade.php --}}
    @props([
        'title' => 'Default Chart Title',
        'chartId' => 'defaultChartId' . rand(1000,9999), // Unique ID for chart canvas
        'infoText' => null,
        'buttonText' => null,
        'buttonUrl' => '#',
        'chartHeight' => 'h-64 sm:h-72 md:h-80' // Default height class for chart container
    ])

    <div class="app-card rounded-lg shadow p-4 md:p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-[var(--app-text-primary)]">{{ $title }}</h3>
            @if($buttonText)
            <a href="{{ $buttonUrl }}" class="text-sm bg-primary-accent text-white py-1.5 px-3 rounded-md hover:opacity-90 transition-opacity">
                {{ $buttonText }}
            </a>
            @endif
        </div>

        <div class="{{ $chartHeight }}"> {{-- Container to control chart height --}}
            <canvas id="{{ $chartId }}"></canvas>
        </div>

        @if($infoText)
        <p class="text-xs text-[var(--app-text-secondary)] mt-3 text-center md:text-left">
            {!! $infoText !!} {{-- Using {!! !!} to allow HTML in infoText if needed, e.g., for styled percentages --}}
        </p>
        @endif
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartCanvas_{{ $chartId }} = document.getElementById('{{ $chartId }}');
            if (chartCanvas_{{ $chartId }} && typeof Chart !== 'undefined') { // Check if Chart is loaded
                const ctx_{{ $chartId }} = chartCanvas_{{ $chartId }}.getContext('2d');
                // Placeholder: Replace with actual chart data and configuration
                new Chart(ctx_{{ $chartId }}, {
                    type: 'bar', // Example: bar chart
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'], // Example labels
                        datasets: [{
                            label: '{{ $title }} Data',
                            data: [65, 59, 80, 81, 56, 55, 40].sort(() => 0.5 - Math.random()).slice(0,7), // Example random data
                            backgroundColor: 'rgba(109, 40, 217, 0.6)', // Primary accent with opacity (violet-700)
                            borderColor: 'rgba(109, 40, 217, 1)',     // Primary accent
                            borderWidth: 1,
                            borderRadius: 4, // Rounded bars
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: getComputedStyle(document.documentElement).getPropertyValue('--app-border-color').trim() // Use theme border color
                                },
                                ticks: {
                                    color: getComputedStyle(document.documentElement).getPropertyValue('--app-text-secondary').trim() // Use theme text color
                                }
                            },
                            x: {
                                grid: {
                                    display: false // Hide x-axis grid lines for cleaner look
                                },
                                ticks: {
                                    color: getComputedStyle(document.documentElement).getPropertyValue('--app-text-secondary').trim()
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false // Often hide legend for single dataset bar charts in dashboards
                            },
                            tooltip: {
                                backgroundColor: 'rgba(31, 41, 55, 0.9)', // Darker tooltip
                                titleColor: '#F3F4F6',
                                bodyColor: '#F3F4F6',
                                borderColor: 'rgba(55, 65, 81, 0.9)',
                                borderWidth: 1
                            }
                        }
                    }
                });
            } else if (chartCanvas_{{ $chartId }}) {
                // Fallback if Chart.js is not loaded or for simple display
                chartCanvas_{{ $chartId }}.parentElement.style.display = 'flex';
                chartCanvas_{{ $chartId }}.parentElement.style.alignItems = 'center';
                chartCanvas_{{ $chartId }}.parentElement.style.justifyContent = 'center';
                chartCanvas_{{ $chartId }}.parentElement.innerHTML = '<span class="text-xs text-[var(--app-text-secondary)]">(Chart: {{ $title }})</span>';
            }
        });
    </script>
    @endpush
