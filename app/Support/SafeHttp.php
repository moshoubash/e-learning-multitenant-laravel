<?php

namespace App\Support;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;

/**
 * SSRF-safe HTTP client factory.
 *
 * Wraps Laravel's HTTP client to validate URLs before sending.
 * If a caller accidentally passes a user-controlled URL (webhook
 * target, profile picture, oEmbed, etc.), the request is rejected
 * before any network traffic leaves the box.
 *
 * Mitigates OWASP A10:2021 - Server-Side Request Forgery (SSRF).
 */
class SafeHttp
{
    public function __construct(private readonly Factory $factory) {}

    public function get(string $url, array $query = []): \Illuminate\Http\Client\Response
    {
        return $this->make()->get(SafeUrl::assert($url), $query);
    }

    public function post(string $url, array $body = []): \Illuminate\Http\Client\Response
    {
        return $this->make()->post(SafeUrl::assert($url), $body);
    }

    public function head(string $url): \Illuminate\Http\Client\Response
    {
        return $this->make()->head(SafeUrl::assert($url));
    }

    private function make(): PendingRequest
    {
        return $this->factory
            ->timeout(5)
            ->connectTimeout(2)
            ->retry(2, 250)
            ->withHeaders(['User-Agent' => config('app.name') . ' SSRF-safe client']);
    }
}
