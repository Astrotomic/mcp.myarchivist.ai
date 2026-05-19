<?php

namespace Tests\Feature\Http\Controllers\WellKnown;

use App\Http\Controllers\WellKnown\ShowMcpServerCardController;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShowMcpServerCardControllerTest extends TestCase
{
    #[Test]
    public function it_returns_server_card(): void
    {
        $this->getJson(action(ShowMcpServerCardController::class))
            ->assertOk()
            ->assertJson(function (AssertableJson $json): void {
                $json
                    ->whereType('serverInfo.name', 'string')
                    ->whereType('serverInfo.version', 'string')
                    ->whereType('authentication.required', 'boolean')
                    ->where('authentication.required', true)
                    ->whereType('authentication.schemes', 'array')
                    ->where('authentication.schemes', ['oauth2'])
                    ->whereType('tools', 'array')
                    ->whereType('resources', 'array')
                    ->whereType('prompts', 'array');
            });
    }
}
