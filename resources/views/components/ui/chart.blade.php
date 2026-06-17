{{-- Chart component (Chart.js) --}}
@props([
    'type' => 'line', // line, bar, doughnut, pie
    'title' => null,
    'labels' => [], // array
    'datasets' => [], // array of { label, data, color, backgroundColor, borderColor }
    'height' => 260,
])

@php
    $chartId = 'chart-' . uniqid();
@endphp

<div {{ $attributes->merge(['class' => 'bg-surface-container-lowest neo-radius p-5']) }}>
    @if($title)
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface">{{ $title }}</h3>
        </div>
    @endif

    <div class="relative" style="height: {{ (int) $height }}px;">
        <canvas id="{{ $chartId }}" wire:ignore></canvas>
    </div>

    <script>
        (function () {
            const el = document.getElementById(@json($chartId));
            if (!el || el.dataset.initialized === '1') return;
            el.dataset.initialized = '1';

            const palette = ['#FFD600', '#0A0A0A', '#5f5e5e', '#ba1a1a', '#705d00', '#E2E2E2', '#333333', '#8B8000'];
            const labels = @json($labels);
            const rawDatasets = @json($datasets);
            const chartType = @json($type);

            const datasets = rawDatasets.map((ds, idx) => {
                const color = ds.color || palette[idx % palette.length];
                const base = {
                    label: ds.label || '',
                    data: ds.data || [],
                    backgroundColor: ds.backgroundColor || (chartType === 'line' ? color + '33' : color),
                    borderColor: ds.borderColor || color,
                    borderWidth: ds.borderWidth ?? (chartType === 'line' ? 2 : 1),
                };

                if (chartType === 'line') {
                    base.tension = 0.35;
                    base.fill = true;
                    base.pointBackgroundColor = color;
                    base.pointRadius = 3;
                    base.pointHoverRadius = 5;
                }

                return base;
            });

            const horizontal = rawDatasets[0] && rawDatasets[0].horizontal === true;

            const config = {
                type: chartType,
                data: { labels, datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: chartType === 'bar' && horizontal ? 'y' : 'x',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                boxHeight: 10,
                                padding: 12,
                                color: '#5f5e5e',
                                font: { size: 11, weight: 'bold' }
                            }
                        }
                    },
                    scales: (chartType === 'doughnut' || chartType === 'pie') ? {} : {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#5f5e5e', font: { size: 11, weight: 'bold' } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#E2E2E2' },
                            ticks: { color: '#5f5e5e', font: { size: 11, weight: 'bold' }, precision: 0 }
                        }
                    }
                }
            };

            new Chart(el.getContext('2d'), config);
        })();
    </script>
</div>
