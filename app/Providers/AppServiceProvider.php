<?php

namespace App\Providers;

use App\Http\Middleware\ArchivistPassthroughMiddleware;
use App\Services\ArchivistClient;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ArchivistClient::class, function ($app) {
            /** @var Request|null $request */
            $request = $app->bound('request') ? $app->make('request') : null;

            return new ArchivistClient($request);
        });
    }

    public function boot(): void
    {
        /** @var Router $router */
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('archivist.passthrough', ArchivistPassthroughMiddleware::class);
    }
}
