<?php

namespace App\Services;

use App\Exceptions\ArchivistApiException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ArchivistClient
{
    public function __construct(
        private readonly string $token,
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
            ->timeout(30)
            ->connectTimeout(10)
            ->acceptJson()
            ->withToken($this->token)
            ->withHeader('x-api-key', $this->token);
    }
}
