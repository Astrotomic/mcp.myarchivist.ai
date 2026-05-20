<?php

namespace Tests;

use App\Services\ArchivistClient;

abstract class GummibeerTestCase extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->instance(ArchivistClient::class, new ArchivistClient(env('GUMMIBEER_TOKEN')));
    }
}
