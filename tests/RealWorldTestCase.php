<?php

namespace Tests;

use App\Mcp\Servers\ArchivistServer;
use App\Services\ArchivistClient;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Mcp\Server\Tool;

abstract class RealWorldTestCase extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $suite = explode('\\', static::class)[1];
        $token = env(Str::of($suite)->snake()->upper()->append('_TOKEN')->toString()) ?: 'fixture-token';

        $this->app->instance(ArchivistClient::class, new ArchivistClient($token));
    }

    /**
     * @param  class-string<Tool>  $tool
     * @param  array<string, mixed>  $arguments
     * @param  class-string  $schema
     * @param  array<string, mixed>  $expected
     */
    protected function assertToolReturnsData(
        string $tool,
        array $arguments,
        string $schema,
        array $expected = [],
        bool $snapshot = true,
    ): void {
        ArchivistServer::tool($tool, $arguments)
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json) use ($schema, $expected, $snapshot): void {
                $json->assertJsonSchema($schema);

                foreach ($expected as $key => $value) {
                    $json->where($key, $value);
                }

                if ($snapshot) {
                    $this->assertMatchesJsonSnapshot($json);
                }
            });
    }

    /**
     * @param  class-string<Tool>  $tool
     * @param  array<string, mixed>  $arguments
     * @param  class-string  $schema
     * @param  array<string, mixed>  $expected
     */
    protected function assertToolReturnsPaginatedData(
        string $tool,
        array $arguments,
        string $schema,
        array $expected,
    ): void {
        ArchivistServer::tool($tool, $arguments)
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json) use ($schema, $expected): void {
                $json->assertPaginatedList(function (AssertableJson $item) use ($schema, $expected): void {
                    $item->assertJsonSchema($schema);

                    foreach ($expected as $key => $value) {
                        $item->where($key, $value);
                    }
                });

                $this->assertMatchesJsonSnapshot($json);
            });
    }

    /**
     * @param  class-string<Tool>  $tool
     * @param  array<string, mixed>  $arguments
     */
    protected function assertToolReturnsErrors(string $tool, array $arguments): void
    {
        ArchivistServer::tool($tool, $arguments)->assertHasErrors();
    }
}
