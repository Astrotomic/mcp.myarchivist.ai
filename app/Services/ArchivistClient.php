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
            ->acceptJson()
            ->when(
                $this->request?->bearerToken(),
                fn (PendingRequest $request, string $token) => $request->withToken($token)
            );
    }
}
