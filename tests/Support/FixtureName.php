<?php

namespace Tests\Support;

use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;

final class FixtureName
{
    public static function forHttpRequest(TestCase $test, HttpRequest $request): string
    {
        $query = [];
        $queryString = parse_url($request->url(), PHP_URL_QUERY);

        if (filled($queryString)) {
            parse_str($queryString, $query);
        }

        return implode('/', [
            str_replace('.', '-', (string) parse_url($request->url(), PHP_URL_HOST)),
            trim((string) parse_url($request->url(), PHP_URL_PATH), '/'),
            strtoupper($request->method()),
            hash('md5', $test::class),
            hash('md5', json_encode(Arr::sortRecursive(array_merge($request->data(), $query)), JSON_THROW_ON_ERROR)),
        ]);
    }
}
