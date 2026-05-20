<?php

namespace Tests\Gummibeer;

use App\Services\ArchivistClient;
use Tests\ApiTestCase;

abstract class GummibeerTestCase extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->instance(ArchivistClient::class, new ArchivistClient(env('GUMMIBEER_TOKEN')));
    }
}
