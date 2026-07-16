<?php

namespace Tests\Support;

use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Arr;

final class FixtureName
{
    public static function forHttpRequest(HttpRequest $request): string
    {
        $query = [];
        $queryString = parse_url($request->url(), PHP_URL_QUERY);

        if (filled($queryString)) {
            parse_str($queryString, $query);
        }

        return self::build(
            url: $request->url(),
            method: $request->method(),
            payload: array_merge($request->data(), $query),
        );
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function build(string $url, string $method, array $payload = []): string
    {
        return implode('/', [
            str_replace('.', '-', (string) parse_url($url, PHP_URL_HOST)),
            trim((string) parse_url($url, PHP_URL_PATH), '/'),
            strtoupper($method),
            hash('md5', json_encode(Arr::sortRecursive($payload), JSON_THROW_ON_ERROR)),
        ]);
    }
}
