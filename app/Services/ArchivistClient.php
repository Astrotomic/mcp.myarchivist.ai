<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ArchivistClient
{
    public function __construct(
        private readonly string $token,
    ) {}

    public function get(string $path, array $query = [], ?int $timeout = null): Response
    {
        $request = Http::archivist(token: $this->token);

        if ($timeout !== null) {
            $request = $request->timeout($timeout);
        }

        return $request->get(
            url: $path,
            query: collect($query)
                ->reject(fn (mixed $value) => $value === null)
                ->map(fn (mixed $value) => is_bool($value) ? json_encode($value) : $value)
                ->all()
        );
    }
}
