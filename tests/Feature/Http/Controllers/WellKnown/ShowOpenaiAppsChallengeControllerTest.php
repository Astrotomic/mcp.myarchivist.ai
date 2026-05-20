<?php

namespace Tests\Feature\Http\Controllers\WellKnown;

use App\Http\Controllers\WellKnown\ShowOpenaiAppsChallengeController;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class ShowOpenaiAppsChallengeControllerTest extends FeatureTestCase
{
    #[Test]
    public function it_returns_configured_challenge_token(): void
    {
        config(['services.openai.apps_challenge_token' => 'test-openai-token']);

        $this->get(action(ShowOpenaiAppsChallengeController::class))
            ->assertOk()
            ->assertHeader('content-type', 'text/plain; charset=utf-8')
            ->assertHeader('access-control-allow-origin', '*')
            ->assertSeeText('test-openai-token');
    }

    #[Test]
    public function it_returns_not_found_when_token_is_missing(): void
    {
        config(['services.openai.apps_challenge_token' => '']);

        $this->get(action(ShowOpenaiAppsChallengeController::class))
            ->assertNotFound();
    }
}
