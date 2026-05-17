<?php

namespace App\Services;

use App\Exceptions\ArchivistApiException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ArchivistClient
{
    public function __construct(
        private readonly ?Request $request = null,
    ) {}

    public function get(string $path, array $query = []): array
    {
        $response = $this->pending()
            ->get($path, $query);

        if ($response->failed()) {
            throw new ArchivistApiException(
                status: $response->status(),
                detail: $response->json('detail') ?? $response->body(),
            );
        }

        return $response->json() ?? [];
    }

    private function pending(): PendingRequest
    {
        return Http::baseUrl((string) config('services.archivist.base_url'))
            ->withToken($this->resolveApiKey())
            ->acceptJson();
    }

    private function resolveApiKey(): string
    {
        // Web mode: Bearer token forwarded from the MCP client request.
        $fromRequest = $this->request?->attributes->get('archivist_api_key');

        if ($fromRequest !== null && $fromRequest !== '') {
            logger()->debug('ArchivistClient: using request token', [
                'token_length' => strlen($fromRequest),
                'token_prefix' => substr($fromRequest, 0, 8) . '...',
            ]);
            return (string) $fromRequest;
        }

        $envKey = (string) config('services.archivist.api_key');
        logger()->debug('ArchivistClient: using env fallback', [
            'has_key' => $envKey !== '',
        ]);
        return $envKey;
    }
}
