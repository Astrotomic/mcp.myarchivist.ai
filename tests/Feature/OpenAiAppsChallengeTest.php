<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class OpenAiAppsChallengeTest extends FeatureTestCase
{
    public function test_openai_apps_challenge_returns_configured_token(): void
    {
        config(['services.openai.apps_challenge_token' => 'test-openai-token']);

        $this->get('/.well-known/openai-apps-challenge')
            ->assertOk()
            ->assertHeader('content-type', 'text/plain; charset=utf-8')
            ->assertHeader('access-control-allow-origin', '*')
            ->assertSeeText('test-openai-token');
    }

    public function test_openai_apps_challenge_returns_not_found_when_token_is_missing(): void
    {
        config(['services.openai.apps_challenge_token' => null]);

        $this->get('/.well-known/openai-apps-challenge')
            ->assertNotFound();
    }
}
