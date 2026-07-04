@php
    $service = app(\App\Services\DesignConfigService::class);
    $colors = $service->getColors();

    $hexToRgb = function ($hex) {
        $hex = ltrim($hex, '#');
        if (strlen($hex) !== 6) return '0 0 0';
        return hexdec(substr($hex, 0, 2)) . ' ' . hexdec(substr($hex, 2, 2)) . ' ' . hexdec(substr($hex, 4, 2));
    };

    $authColors = [
        'surface-container-low' => $colors['auth_body_bg'] ?? '#F3F3F3',
        'surface-container-lowest' => $colors['auth_card_bg'] ?? '#FFFFFF',
        'primary-container' => $colors['auth_primary'] ?? '#FFD600',
        'on-primary-container' => $colors['auth_on_primary'] ?? '#705d00',
        'on-surface' => $colors['auth_text'] ?? '#0A0A0A',
        'secondary' => $colors['auth_secondary'] ?? '#5f5e5e',
        'error' => $colors['auth_error'] ?? '#ba1a1a',
        'auth-border' => $colors['auth_border'] ?? '#0A0A0A',
    ];
@endphp

<style id="auth-dynamic-design-css">
    :root {
        @foreach ($authColors as $key => $hex)
            --color-{{ $key }}: {{ $hex }};
            --color-{{ $key }}-rgb: {{ $hexToRgb($hex) }};
        @endforeach
    }
</style>
