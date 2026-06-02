<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class RegisterOauthClientControllerTest extends FeatureTestCase
{
    #[Test]
    public function it_handles_options_preflight(): void
    {
        $this->options('/oauth/register')
            ->assertNoContent()
            ->assertHeader('Access-Control-Allow-Origin', '*')
            ->assertHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
    }

    #[Test]
    public function it_proxies_dynamic_client_registration_to_the_app(): void
    {
        Http::fake([
            'https://app.myarchivist.ai/api/oauth/register' => Http::response([
                'client_id' => 'dcr_test123',
                'client_name' => 'ChatGPT',
                'redirect_uris' => ['https://chatgpt.com/connector/oauth/test'],
                'grant_types' => ['authorization_code'],
                'response_types' => ['code'],
                'token_endpoint_auth_method' => 'none',
                'scopes_supported' => ['profile', 'worlds_read'],
            ], 201),
        ]);

        $this->postJson('/oauth/register', [
            'client_name' => 'ChatGPT',
            'redirect_uris' => ['https://chatgpt.com/connector/oauth/test'],
            'grant_types' => ['authorization_code'],
            'response_types' => ['code'],
            'token_endpoint_auth_method' => 'none',
            'scope' => 'profile worlds_read',
        ])
            ->assertCreated()
            ->assertHeader('Access-Control-Allow-Origin', '*')
            ->assertJsonPath('client_id', 'dcr_test123')
            ->assertJsonPath('scope', 'profile worlds_read');

        Http::assertSent(fn ($request) => $request->url() === 'https://app.myarchivist.ai/api/oauth/register'
            && $request['client_name'] === 'ChatGPT');
    }

    #[Test]
    public function it_serves_dynamic_client_registration_at_the_api_path_alias(): void
    {
        Http::fake([
            'https://app.myarchivist.ai/api/oauth/register' => Http::response([
                'client_id' => 'dcr_alias123',
                'client_name' => 'ChatGPT',
                'redirect_uris' => ['https://chatgpt.com/connector/oauth/test'],
                'grant_types' => ['authorization_code'],
                'response_types' => ['code'],
                'token_endpoint_auth_method' => 'none',
            ], 201),
        ]);

        $this->postJson('/api/oauth/register', [
            'client_name' => 'ChatGPT',
            'redirect_uris' => ['https://chatgpt.com/connector/oauth/test'],
        ])
            ->assertCreated()
            ->assertJsonPath('client_id', 'dcr_alias123');
    }
}
