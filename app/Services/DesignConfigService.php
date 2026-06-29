<?php

namespace App\Services;

use App\Models\Tenant\DesignConfig;
use Illuminate\Support\Facades\Storage;

class DesignConfigService
{
    private const DEFAULTS = [
        'primary_container' => '#FFD600',
        'on_surface' => '#0A0A0A',
        'surface_container_lowest' => '#FFFFFF',
        'surface_container_low' => '#F3F3F3',
        'surface_container' => '#EEEEEE',
        'surface_container_high' => '#E8E8E8',
        'surface_container_highest' => '#E2E2E2',
        'secondary' => '#5f5e5e',
        'error' => '#ba1a1a',
        'on_primary_container' => '#705d00',
        'auth_body_bg' => '#F3F3F3',
        'auth_card_bg' => '#FFFFFF',
        'auth_primary' => '#FFD600',
        'auth_on_primary' => '#705d00',
        'auth_text' => '#0A0A0A',
        'auth_secondary' => '#5f5e5e',
        'auth_border' => '#0A0A0A',
        'auth_error' => '#ba1a1a',
    ];

    private const CHART_DEFAULTS = ['#FFD600', '#0A0A0A', '#5f5e5e', '#ba1a1a', '#705d00', '#E2E2E2'];

    public function getColors(): array
    {
        $config = DesignConfig::first();

        return $config ? $config->colors : self::DEFAULTS;
    }

    public function getColor(string $key, ?string $default = null): string
    {
        $colors = $this->getColors();

        return $colors[$key] ?? ($default ?? self::DEFAULTS[$key] ?? '#000000');
    }

    public function getChartColors(): array
    {
        $colors = $this->getColors();

        return $colors['chart_colors'] ?? self::CHART_DEFAULTS;
    }

    public function saveColors(array $colors): DesignConfig
    {
        $config = DesignConfig::first();

        if ($config) {
            $config->update(['colors' => $colors]);
        } else {
            $config = DesignConfig::create(['colors' => $colors]);
        }

        return $config;
    }

    public function getCssVariables(): string
    {
        $colors = $this->getColors();
        $css = '';

        foreach ($colors as $key => $value) {
            if ($key === 'chart_colors') {
                continue;
            }
            $varName = '--color-' . str_replace('_', '-', $key);
            $css .= "    {$varName}: {$value};\n";
        }

        $chartColors = $this->getChartColors();
        foreach ($chartColors as $i => $color) {
            $css .= "    --color-chart-{$i}: {$color};\n";
        }

        return $css;
    }

    public function getLogo(): ?string
    {
        $config = DesignConfig::first();

        return $config?->logo;
    }

    public function saveLogo(string $path): DesignConfig
    {
        $config = DesignConfig::first();

        $oldLogo = $config?->logo;

        if ($config) {
            $config->update(['logo' => $path]);
        } else {
            $config = DesignConfig::create(['colors' => self::DEFAULTS, 'logo' => $path]);
        }

        if ($oldLogo && $oldLogo !== $path) {
            Storage::disk('s3')->delete($oldLogo);
        }

        return $config;
    }

    public function deleteLogo(): void
    {
        $config = DesignConfig::first();

        if ($config?->logo) {
            Storage::disk('s3')->delete($config->logo);
            $config->update(['logo' => null]);
        }
    }
}
