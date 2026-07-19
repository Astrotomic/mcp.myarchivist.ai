<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ArchivistClient
{
    public function __construct(
        private readonly string $token,
    ) {}

    public function get(string $path, array $query = [], ?int $timeout = null): Response
    {
        return $this->request($timeout)->get(
            url: $path,
            query: $this->normalizeQuery($query),
        );
    }

    public function post(string $path, array $body = [], ?int $timeout = null): Response
    {
        return $this->request($timeout)->post($path, $this->normalizeBody($body));
    }

    public function patch(string $path, array $body = [], ?int $timeout = null): Response
    {
        return $this->request($timeout)->patch($path, $this->normalizeBody($body));
    }

    public function put(string $path, array $body = [], ?int $timeout = null): Response
    {
        return $this->request($timeout)->put($path, $this->normalizeBody($body));
    }

    public function delete(string $path, array $query = [], ?int $timeout = null): Response
    {
        return $this->request($timeout)->delete(
            url: $this->appendQuery($path, $this->normalizeQuery($query)),
        );
    }

    public function deleteJson(string $path, array $body = [], ?int $timeout = null): Response
    {
        return $this->request($timeout)->delete($path, $this->normalizeBody($body));
    }

    private function request(?int $timeout = null): PendingRequest
    {
        $request = Http::archivist(token: $this->token);

        if ($timeout !== null) {
            $request = $request->timeout($timeout);
        }

        return $request;
    }

    private function normalizeQuery(array $query): array
    {
        return collect($query)
            ->reject(fn (mixed $value) => $value === null)
            ->map(fn (mixed $value) => is_bool($value) ? json_encode($value) : $value)
            ->all();
    }

    private function normalizeBody(array $body): array
    {
        return collect($body)
            ->reject(fn (mixed $value) => $value === null)
            ->all();
    }

    private function appendQuery(string $path, array $query): string
    {
        if (empty($query)) {
            return $path;
        }

        $separator = str_contains($path, '?') ? '&' : '?';

        return $path.$separator.http_build_query($query);
    }
}
