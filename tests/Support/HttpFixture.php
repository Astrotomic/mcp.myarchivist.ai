<?php

namespace Tests\Support;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Http\Client\Response as HttpResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;

final class HttpFixture
{
    private const string DIRECTORY = 'tests/Fixtures/Http';

    public static function pathFor(TestCase $test, HttpRequest $request): string
    {
        return base_path(self::DIRECTORY.'/'.FixtureName::forHttpRequest($test, $request).'.json');
    }

    public static function exists(TestCase $test, HttpRequest $request): bool
    {
        return File::exists(self::pathFor($test, $request));
    }

    public static function toClientResponse(TestCase $test, HttpRequest $request): PromiseInterface
    {
        /** @var array{statusCode: int, headers: array<string, mixed>, data: string} $fixture */
        $fixture = json_decode(
            File::get(self::pathFor($test, $request)),
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        return Http::response(
            $fixture['data'],
            $fixture['statusCode'],
            $fixture['headers'],
        );
    }

    public static function store(TestCase $test, HttpRequest $request, HttpResponse $response): void
    {
        $path = self::pathFor($test, $request);

        File::ensureDirectoryExists(dirname($path));
        File::put($path, json_encode([
            'statusCode' => $response->status(),
            'headers' => $response->headers(),
            'data' => $response->body(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR).PHP_EOL);
    }
}
