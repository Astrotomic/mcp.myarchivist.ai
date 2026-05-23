<?php

namespace App\Services;

use App\Exceptions\ArchivistApiException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ArchivistClient
{
    public function __construct(
        private readonly string $token,
    ) {}

    /**
     * @throws ArchivistApiException
     */
    public function get(string $path, array $query = []): Response
    {
        try {
            $response = $this->pending()->get(
                url: $path,
                query: $this->normalizeQuery($query)
            );
        } catch (ConnectionException $e) {
            throw new ArchivistApiException(
                status: 0,
                detail: $e->getMessage(),
                previous: $e,
            );
        }

        if ($response->failed()) {
            throw new ArchivistApiException(
                status: $response->status(),
                detail: $response->fluent()->string('detail', $response->body()),
            );
        }

        return $response;
    }



    /**
     * @param  array<int, array{path: string, query?: array, key?: string}>  $requests
     * @return array<string, Response>
     *
     * @throws ArchivistApiException
     */
    public function getPool(array $requests, int $concurrency = 10): array
    {
        try {
            $responses = $this->pending()->pool(
                fn ($pool) => collect($requests)
                    ->mapWithKeys(function (array $request, int $index) use ($pool): array {
                        $key = $request['key'] ?? (string) $index;

                        return [
                            $key => $pool->as($key)->get(
                                url: $request['path'],
                                query: $this->normalizeQuery($request['query'] ?? []),
                            ),
                        ];
                    })
                    ->all(),
                $concurrency,
            );
        } catch (ConnectionException $e) {
            throw new ArchivistApiException(
                status: 0,
                detail: $e->getMessage(),
                previous: $e,
            );
        }

        foreach ($responses as $key => $response) {
            if ($response->failed()) {
                throw new ArchivistApiException(
                    status: $response->status(),
                    detail: $response->fluent()->string('detail', $response->body()),
                );
            }
        }

        return $responses;
    }

    private function normalizeQuery(array $query): array
    {
        return collect($query)
            ->reject(fn (mixed $value) => $value === null)
            ->map(fn (mixed $value) => is_bool($value) ? json_encode($value) : $value)
            ->all();
    }

    private function pending(): PendingRequest
    {
        return Http::baseUrl(config()->string('services.archivist.base_url'))
            ->timeout(30)
            ->connectTimeout(3)
            ->acceptJson()
            ->when(
                value: app()->runningUnitTests(),
                callback: fn (PendingRequest $request) => $request->withHeader('x-api-key', $this->token),
                default: fn (PendingRequest $request) => $request->withToken($this->token),
            );
    }
}
