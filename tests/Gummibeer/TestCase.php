<?php

namespace Tests\Gummibeer;

use App\Services\ArchivistClient;

abstract class TestCase extends \Tests\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->instance(ArchivistClient::class, new ArchivistClient(env('ARCHIVIST_TOKEN')));
    }
}
