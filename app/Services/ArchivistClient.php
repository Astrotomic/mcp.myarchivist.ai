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
        $request = Http::baseUrl((string) config('services.archivist.base_url'))
            ->acceptJson();

        $oauthToken = $this->request?->attributes->get('archivist_api_key');

        if ($oauthToken !== null && $oauthToken !== '') {
            return $request->withToken($oauthToken);
        }

        $apiKey = (string) config('services.archivist.api_key');
        if ($apiKey !== '') {
            return $request->withHeaders(['x-api-key' => $apiKey]);
        }

        return $request;
    }
}
