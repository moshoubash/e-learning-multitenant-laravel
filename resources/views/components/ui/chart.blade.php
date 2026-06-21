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

            const cssVar = (name, fallback) => getComputedStyle(document.documentElement).getPropertyValue(name).trim() || fallback;
            const palette = [
                cssVar('--color-primary-container', '#FFD600'),
                cssVar('--color-on-surface', '#0A0A0A'),
                cssVar('--color-secondary', '#5f5e5e'),
                cssVar('--color-error', '#ba1a1a'),
                cssVar('--color-on-primary-container', '#705d00'),
                cssVar('--color-surface-container-highest', '#E2E2E2'),
                '#333333', '#8B8000'
            ];
            const secondaryColor = cssVar('--color-secondary', '#5f5e5e');
            const gridColor = cssVar('--color-surface-container-highest', '#E2E2E2');
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
                                color: secondaryColor,
                                font: { size: 11, weight: 'bold' }
                            }
                        }
                    },
                    scales: (chartType === 'doughnut' || chartType === 'pie') ? {} : {
                        x: {
                            grid: { display: false },
                            ticks: { color: secondaryColor, font: { size: 11, weight: 'bold' } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor },
                            ticks: { color: secondaryColor, font: { size: 11, weight: 'bold' }, precision: 0 }
                        }
                    }
                }
            };

            new Chart(el.getContext('2d'), config);
        })();
    </script>
</div>
