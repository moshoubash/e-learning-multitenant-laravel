<?php

namespace App\Services\Student;

use App\Models\Tenant\Integration;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalService
{
    protected ?PayPalClient $client = null;
    protected ?Integration $config = null;

    public function __construct()
    {
        $this->config = Integration::getProviderConfig('paypal');
    }

    public function isConfigured(): bool
    {
        return Integration::isProviderConfigured('paypal');
    }

    public function getClient(): PayPalClient
    {
        if ($this->client === null) {
            $integration = $this->config;
            if (!$integration || !$integration->is_active) {
                throw new \RuntimeException('PayPal is not configured or inactive.');
            }

            $mode = config('paypal.mode', 'sandbox');
            $config = config('paypal');
            $config[$mode]['client_id'] = $integration->client_id;
            $config[$mode]['client_secret'] = $integration->client_secret;

            $client = new PayPalClient($config);
            $client->getAccessToken();

            $this->client = $client;
        }

        return $this->client;
    }

    public function createOrder(float $amount, string $returnUrl, string $cancelUrl): array
    {
        $client = $this->getClient();

        $order = $client->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => config('paypal.currency', 'USD'),
                        'value' => number_format($amount, 2, '.', ''),
                    ],
                ],
            ],
            'application_context' => [
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
                'brand_name' => config('app.name', 'Grid LMS'),
                'user_action' => 'PAY_NOW',
            ],
        ]);

        if (!empty($order['id'])) {
            $approvalLink = collect($order['links'])->firstWhere('rel', 'approve');

            return [
                'success' => true,
                'order_id' => $order['id'],
                'approval_url' => $approvalLink['href'] ?? null,
            ];
        }

        return [
            'success' => false,
            'error' => $order['message'] ?? 'Failed to create PayPal order.',
        ];
    }

    public function captureOrder(string $orderId): array
    {
        $client = $this->getClient();

        $response = $client->capturePaymentOrder($orderId);

        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            $capture = $response['purchase_units'][0]['payments']['captures'][0] ?? [];

            return [
                'success' => true,
                'transaction_id' => $capture['id'] ?? $response['id'],
                'status' => $response['status'],
                'response' => $response,
            ];
        }

        return [
            'success' => false,
            'error' => $response['message'] ?? 'Failed to capture PayPal payment.',
            'status' => $response['status'] ?? 'UNKNOWN',
            'response' => $response,
        ];
    }
}
