<?php

namespace Tests\Concerns;

use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Assert;
use Tests\Support\HttpFixture;

trait InteractsWithHttpFixtures
{
    protected function setUpHttpFixtures(): void
    {
        Http::preventStrayRequests();
        Http::record();

        if ($this->shouldRecordHttpFixtures()) {
            Http::allowStrayRequests();

            return;
        }

        Http::fake(function (HttpRequest $request) {
            if (! HttpFixture::exists($request)) {
                Assert::fail(sprintf(
                    'Missing HTTP fixture [%s]. Record it with: RECORD_HTTP_FIXTURES=1 ./php artisan test --filter=%s',
                    HttpFixture::pathFor($request),
                    static::class,
                ));
            }

            return HttpFixture::toClientResponse($request);
        });
    }

    protected function tearDownHttpFixtures(): void
    {
        if (! $this->shouldRecordHttpFixtures()) {
            return;
        }

        foreach (Http::recorded() as [$request, $response]) {
            if ($response === null
                || in_array($response->status(), [408, 429], true)
                || $response->serverError()
            ) {
                continue;
            }

            HttpFixture::store($request, $response);
        }
    }

    protected function shouldRecordHttpFixtures(): bool
    {
        return filter_var(
            getenv('RECORD_HTTP_FIXTURES') ?: getenv('RECORD_FIXTURES') ?: false,
            FILTER_VALIDATE_BOOL,
        );
    }
}
