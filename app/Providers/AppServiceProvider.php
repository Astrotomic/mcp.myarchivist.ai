<?php

namespace App\Providers;

use App\Exceptions\ArchivistApiException;
use App\Services\ArchivistClient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ArchivistClient::class, function (Application $app): ArchivistClient {
            /** @var Request|null $request */
            $request = $app->bound('request') ? $app->make('request') : null;

            return new ArchivistClient($request?->bearerToken() ?? '');
        });
    }

    public function boot(): void
    {
        if (! $this->app->environment('local')) {
            URL::forceHttps();
        }

        URL::macro('toApp', function (string $path, array $parameters = [], array $query = []): string {
            $path = strtr(
                $path,
                collect($parameters)->keyBy(fn (mixed $_, string $key): string => Str::of($key)->start('{')->finish('}'))->all(),
            );

            $url = rtrim(config()->string('services.archivist.app_url'), '/').'/'.ltrim($path, '/');

            if (Str::contains($url, '?')) {
                $existingQuery = [];
                parse_str(Str::after($url, '?'), $existingQuery);
                $query = array_merge($query, $existingQuery);
            }

            if (! empty($query)) {
                $url = Str::before($url, '?').'?'.http_build_query($query);
            }

            return $url;
        });

        Http::macro('archivist', function (?string $token = null, ?PendingRequest $request = null): PendingRequest {
            $token = $token ?? request()->bearerToken();

            if (empty($token)) {
                throw new HttpClientException('Archivist API token is missing.');
            }

            return ($request ?? Http::createPendingRequest())
                ->baseUrl(config()->string('services.archivist.base_url'))
                ->timeout(30)
                ->connectTimeout(3)
                ->acceptJson()
                ->throw(function (Response $response, RequestException $exception): never {
                    throw ArchivistApiException::fromResponse($response, $exception);
                })
                ->when(
                    value: app()->runningUnitTests(),
                    callback: fn (PendingRequest $request) => $request->withHeader('x-api-key', $token),
                    default: fn (PendingRequest $request) => $request->withToken($token),
                );
        });
    }
}
