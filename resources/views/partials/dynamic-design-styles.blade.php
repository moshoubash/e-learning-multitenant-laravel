@php
    $service = app(\App\Services\DesignConfigService::class);
    $colors = $service->getColors();
@endphp

<style id="dynamic-design-css">
    :root {
        @foreach ($colors as $key => $value)
            @if ($key === 'chart_colors')
                @foreach ($value as $i => $color)
                    --color-chart-{{ $i }}: {{ $color }};
                @endforeach
            @else
                --color-{{ str_replace('_', '-', $key) }}: {{ $value }};
            @endif
        @endforeach
    }
</style>
