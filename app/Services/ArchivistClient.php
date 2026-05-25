<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ArchivistClient
{
    public function __construct(
        private readonly string $token,
    ) {}

    public function get(string $path, array $query = []): Response
    {
        return Http::archivist(token: $this->token)->get(
            url: $path,
            query: collect($query)
                ->reject(fn (mixed $value) => $value === null)
                ->map(fn (mixed $value) => is_bool($value) ? json_encode($value) : $value)
                ->all()
        );
    }
}
