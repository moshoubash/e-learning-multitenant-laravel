<?php

namespace App\Services;

use App\Models\Tenant\Integration;

class OAuthService
{
    public function isProviderConfigured(string $provider): bool
    {
        return Integration::isProviderConfigured($provider);
    }

    public function getProviderConfig(string $provider): ?array
    {
        $integration = Integration::getProviderConfig($provider);

        if (! $integration || ! $integration->is_active) {
            return null;
        }

        return [
            'client_id' => $integration->client_id,
            'client_secret' => $integration->client_secret,
            'redirect' => $integration->redirect_url,
        ];
    }

    public function applyConfigToServices(string $provider): void
    {
        $config = $this->getProviderConfig($provider);

        if ($config) {
            config([
                "services.{$provider}.client_id" => $config['client_id'],
                "services.{$provider}.client_secret" => $config['client_secret'],
                "services.{$provider}.redirect" => $config['redirect'],
            ]);
        }
    }
}
