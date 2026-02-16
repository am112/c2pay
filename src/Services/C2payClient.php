<?php
declare(strict_types=1);

namespace Am112\C2pay\Services;

use Am112\C2pay\Exceptions\C2payRequestFailedException;
use Illuminate\Support\Facades\Http;

final class C2payClient
{
    public function __construct(private string $domain) {}

    /**
     * @param string $path
     * @param array|string $payload
     * @param array<string,string> $headers
     * @return array|string
     */
    public function post(string $path, array|string $payload, array $headers = ['Content-Type' => 'application/json']): array|string
    {
        $url = $this->domain . $path;
        $response = Http::timeout(30)
            ->withHeaders($headers)
            ->retry(1, 200)
            ->post($url, is_array($payload) ? $payload : $payload);

        if (! $response->successful()) {
            throw new C2payRequestFailedException('C2Pay HTTP error', $response->status());
        }

        $contentType = strtolower($response->header('Content-Type'));
        if (str_contains($contentType, 'application/json') || ($headers['Content-Type'] ?? '') === 'application/json') {
            return $response->json();
        }

        return $response->body();
    }
}
