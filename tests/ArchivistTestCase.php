<?php

namespace Tests;

use App\Services\ArchivistClient;

abstract class ArchivistTestCase extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->instance(ArchivistClient::class, new ArchivistClient(
            env('ARCHIVIST_TOKEN') ?: 'fixture-token',
        ));
    }
}
