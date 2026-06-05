<?php

namespace App\Support;

use InvalidArgumentException;

/**
 * URL safety validator.
 *
 * Rejects URLs that would let a server-side request reach internal
 * infrastructure (cloud metadata, private RFC1918 networks, loopback,
 * link-local, IPv6 transition addresses, non-http(s) schemes).
 *
 * Mitigates OWASP A10:2021 - Server-Side Request Forgery (SSRF).
 */
class SafeUrl
{
    public static function assert(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            throw new InvalidArgumentException('URL is empty.');
        }

        $parts = parse_url($url);

        if ($parts === false || ! isset($parts['scheme'], $parts['host'])) {
            throw new InvalidArgumentException('URL is malformed.');
        }

        if (! in_array(strtolower($parts['scheme']), ['http', 'https'], true)) {
            throw new InvalidArgumentException("URL scheme '{$parts['scheme']}' is not allowed (only http/https).");
        }

        $host = strtolower($parts['host']);

        self::assertHostNotDangerous($host);

        $ips = self::resolveHostIps($host);

        foreach ($ips as $ip) {
            if (self::isPrivateOrReserved($ip)) {
                throw new InvalidArgumentException("URL host '{$host}' resolves to a private/reserved IP ({$ip}); SSRF blocked.");
            }
        }

        return $url;
    }

    private static function assertHostNotDangerous(string $host): void
    {
        $blocked = [
            'localhost',
            'metadata.google.internal',
            '169.254.169.254',
        ];

        if (in_array($host, $blocked, true)) {
            throw new InvalidArgumentException("URL host '{$host}' is blocked.");
        }
    }

    /**
     * @return string[]
     */
    private static function resolveHostIps(string $host): array
    {
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return [$host];
        }

        $records = @dns_get_record($host, DNS_A + DNS_AAAA);

        if (! $records) {
            return [];
        }

        return array_values(array_filter(array_map(
            fn ($r) => $r['ip'] ?? ($r['ipv6'] ?? null),
            $records
        )));
    }

    private static function isPrivateOrReserved(string $ip): bool
    {
        return ! filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );
    }
}
