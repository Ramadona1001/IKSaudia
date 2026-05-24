<?php

namespace App\Services\Http;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

final class SafeHttpClient
{
    public function get(string $url, array $query = []): \Illuminate\Http\Client\Response
    {
        $this->assertAllowedUrl($url);

        return Http::timeout(60)->get($url, $query);
    }

    public function pending(): PendingRequest
    {
        return Http::timeout(60);
    }

    public function assertAllowedUrl(string $url): void
    {
        $host = parse_url($url, PHP_URL_HOST);

        if (! is_string($host) || $host === '') {
            throw new InvalidArgumentException('Invalid URL host.');
        }

        $allowed = config('security.http.allowed_hosts', []);

        foreach ($allowed as $pattern) {
            if ($host === $pattern || str_ends_with($host, '.'.$pattern)) {
                return;
            }
        }

        throw new InvalidArgumentException("Host [{$host}] is not in the HTTP allowlist.");
    }
}
