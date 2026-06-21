<?php

namespace App\Livewire\Admin;

use App\Services\DesignConfigService;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class DesignConfig extends Component
{
    public string $primaryContainer = '#FFD600';
    public string $onSurface = '#0A0A0A';
    public string $surfaceContainerLowest = '#FFFFFF';
    public string $surfaceContainerLow = '#F3F3F3';
    public string $surfaceContainer = '#EEEEEE';
    public string $surfaceContainerHigh = '#E8E8E8';
    public string $surfaceContainerHighest = '#E2E2E2';
    public string $secondary = '#5f5e5e';
    public string $error = '#ba1a1a';
    public string $onPrimaryContainer = '#705d00';
    public string $chart1 = '#FFD600';
    public string $chart2 = '#0A0A0A';
    public string $chart3 = '#5f5e5e';
    public string $chart4 = '#ba1a1a';
    public string $chart5 = '#705d00';
    public string $chart6 = '#E2E2E2';

    public string $authBodyBg = '#F3F3F3';
    public string $authCardBg = '#FFFFFF';
    public string $authPrimary = '#FFD600';
    public string $authOnPrimary = '#705d00';
    public string $authText = '#0A0A0A';
    public string $authSecondary = '#5f5e5e';
    public string $authBorder = '#0A0A0A';
    public string $authError = '#ba1a1a';

    public function mount(DesignConfigService $service)
    {
        $colors = $service->getColors();

        $this->primaryContainer = $colors['primary_container'] ?? '#FFD600';
        $this->onSurface = $colors['on_surface'] ?? '#0A0A0A';
        $this->surfaceContainerLowest = $colors['surface_container_lowest'] ?? '#FFFFFF';
        $this->surfaceContainerLow = $colors['surface_container_low'] ?? '#F3F3F3';
        $this->surfaceContainer = $colors['surface_container'] ?? '#EEEEEE';
        $this->surfaceContainerHigh = $colors['surface_container_high'] ?? '#E8E8E8';
        $this->surfaceContainerHighest = $colors['surface_container_highest'] ?? '#E2E2E2';
        $this->secondary = $colors['secondary'] ?? '#5f5e5e';
        $this->error = $colors['error'] ?? '#ba1a1a';
        $this->onPrimaryContainer = $colors['on_primary_container'] ?? '#705d00';

        $chartColors = $colors['chart_colors'] ?? ['#FFD600', '#0A0A0A', '#5f5e5e', '#ba1a1a', '#705d00', '#E2E2E2'];
        $this->chart1 = $chartColors[0] ?? '#FFD600';
        $this->chart2 = $chartColors[1] ?? '#0A0A0A';
        $this->chart3 = $chartColors[2] ?? '#5f5e5e';
        $this->chart4 = $chartColors[3] ?? '#ba1a1a';
        $this->chart5 = $chartColors[4] ?? '#705d00';
        $this->chart6 = $chartColors[5] ?? '#E2E2E2';

        $this->authBodyBg = $colors['auth_body_bg'] ?? '#F3F3F3';
        $this->authCardBg = $colors['auth_card_bg'] ?? '#FFFFFF';
        $this->authPrimary = $colors['auth_primary'] ?? '#FFD600';
        $this->authOnPrimary = $colors['auth_on_primary'] ?? '#705d00';
        $this->authText = $colors['auth_text'] ?? '#0A0A0A';
        $this->authSecondary = $colors['auth_secondary'] ?? '#5f5e5e';
        $this->authBorder = $colors['auth_border'] ?? '#0A0A0A';
        $this->authError = $colors['auth_error'] ?? '#ba1a1a';
    }

    public function save(DesignConfigService $service)
    {
        $this->validate([
            'primaryContainer' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'onSurface' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'surfaceContainerLowest' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'surfaceContainerLow' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'surfaceContainer' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'surfaceContainerHigh' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'surfaceContainerHighest' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'error' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'onPrimaryContainer' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'chart1' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'chart2' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'chart3' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'chart4' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'chart5' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'chart6' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'authBodyBg' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'authCardBg' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'authPrimary' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'authOnPrimary' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'authText' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'authSecondary' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'authBorder' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'authError' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $service->saveColors([
            'primary_container' => $this->primaryContainer,
            'on_surface' => $this->onSurface,
            'surface_container_lowest' => $this->surfaceContainerLowest,
            'surface_container_low' => $this->surfaceContainerLow,
            'surface_container' => $this->surfaceContainer,
            'surface_container_high' => $this->surfaceContainerHigh,
            'surface_container_highest' => $this->surfaceContainerHighest,
            'secondary' => $this->secondary,
            'error' => $this->error,
            'on_primary_container' => $this->onPrimaryContainer,
            'chart_colors' => [
                $this->chart1,
                $this->chart2,
                $this->chart3,
                $this->chart4,
                $this->chart5,
                $this->chart6,
            ],
            'auth_body_bg' => $this->authBodyBg,
            'auth_card_bg' => $this->authCardBg,
            'auth_primary' => $this->authPrimary,
            'auth_on_primary' => $this->authOnPrimary,
            'auth_text' => $this->authText,
            'auth_secondary' => $this->authSecondary,
            'auth_border' => $this->authBorder,
            'auth_error' => $this->authError,
        ]);

        $this->js('window.location.reload()');
    }

    public function resetDefaults(DesignConfigService $service)
    {
        $this->primaryContainer = '#FFD600';
        $this->onSurface = '#0A0A0A';
        $this->surfaceContainerLowest = '#FFFFFF';
        $this->surfaceContainerLow = '#F3F3F3';
        $this->surfaceContainer = '#EEEEEE';
        $this->surfaceContainerHigh = '#E8E8E8';
        $this->surfaceContainerHighest = '#E2E2E2';
        $this->secondary = '#5f5e5e';
        $this->error = '#ba1a1a';
        $this->onPrimaryContainer = '#705d00';
        $this->chart1 = '#FFD600';
        $this->chart2 = '#0A0A0A';
        $this->chart3 = '#5f5e5e';
        $this->chart4 = '#ba1a1a';
        $this->chart5 = '#705d00';
        $this->chart6 = '#E2E2E2';
        $this->authBodyBg = '#F3F3F3';
        $this->authCardBg = '#FFFFFF';
        $this->authPrimary = '#FFD600';
        $this->authOnPrimary = '#705d00';
        $this->authText = '#0A0A0A';
        $this->authSecondary = '#5f5e5e';
        $this->authBorder = '#0A0A0A';
        $this->authError = '#ba1a1a';

        $service->saveColors([
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
            'chart_colors' => ['#FFD600', '#0A0A0A', '#5f5e5e', '#ba1a1a', '#705d00', '#E2E2E2'],
            'auth_body_bg' => '#F3F3F3',
            'auth_card_bg' => '#FFFFFF',
            'auth_primary' => '#FFD600',
            'auth_on_primary' => '#705d00',
            'auth_text' => '#0A0A0A',
            'auth_secondary' => '#5f5e5e',
            'auth_border' => '#0A0A0A',
            'auth_error' => '#ba1a1a',
        ]);

        $this->js('window.location.reload()');
    }

    public function render()
    {
        return view('livewire.admin.design-config');
    }
}
