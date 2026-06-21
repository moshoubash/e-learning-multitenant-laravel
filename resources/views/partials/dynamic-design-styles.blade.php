@php
    $service = app(\App\Services\DesignConfigService::class);
    $colors = $service->getColors();

    $hexToRgb = function ($hex) {
        $hex = ltrim($hex, '#');
        if (strlen($hex) !== 6) return '0 0 0';
        return hexdec(substr($hex, 0, 2)) . ' ' . hexdec(substr($hex, 2, 2)) . ' ' . hexdec(substr($hex, 4, 2));
    };
@endphp

<style id="dynamic-design-css">
    :root {
        @foreach ($colors as $key => $value)
            @if ($key === 'chart_colors')
                @foreach ($value as $i => $color)
                    --color-chart-{{ $i }}: {{ $color }};
                    --color-chart-{{ $i }}-rgb: {{ $hexToRgb($color) }};
                @endforeach
            @else
                --color-{{ str_replace('_', '-', $key) }}: {{ $value }};
                --color-{{ str_replace('_', '-', $key) }}-rgb: {{ $hexToRgb($value) }};
            @endif
        @endforeach
    }
</style>
