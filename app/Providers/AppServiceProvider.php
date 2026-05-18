<?php

namespace App\Providers;

use App\Services\ArchivistClient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
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

            return new ArchivistClient($request);
        });
    }

    public function boot(): void
    {
        URL::macro('toApp', function (string $path, array $parameters = [], array $query = []): string {
            $path = strtr(
                $path,
                collect($parameters)->keyBy(fn (mixed $_, string $key): string => Str::of($key)->start('{')->finish('}'))->all(),
            );

            $url = rtrim(config('services.archivist.app_url'), '/').'/'.ltrim($path, '/');

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
    }
}
