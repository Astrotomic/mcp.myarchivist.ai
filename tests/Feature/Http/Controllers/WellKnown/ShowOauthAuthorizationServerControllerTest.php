<?php

namespace Tests\Feature\Http\Controllers\WellKnown;

use App\Http\Controllers\WellKnown\ShowOauthAuthorizationServerController;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class ShowOauthAuthorizationServerControllerTest extends FeatureTestCase
{
    #[Test]
    public function it_advertises_local_dynamic_client_registration(): void
    {
        $this->getJson(action(ShowOauthAuthorizationServerController::class))
            ->assertOk()
            ->assertJson(function (AssertableJson $json): void {
                $json
                    ->where('registration_endpoint', route('oauth.register'))
                    ->where('authorization_endpoint', 'https://app.myarchivist.ai/oauth/authorize')
                    ->where('token_endpoint', 'https://app.myarchivist.ai/api/oauth/token')
                    ->etc();
            });
    }

    #[Test]
    public function it_serves_authorization_server_metadata_at_path_suffixed_well_known_urls(): void
    {
        $this->getJson('/.well-known/oauth-authorization-server/mcp')
            ->assertOk()
            ->assertJsonPath('registration_endpoint', route('oauth.register'));

        $this->getJson('/mcp/.well-known/oauth-authorization-server')
            ->assertOk()
            ->assertJsonPath('registration_endpoint', route('oauth.register'));
    }

    #[Test]
    public function it_serves_openid_configuration_as_an_alias_for_oauth_authorization_server_metadata(): void
    {
        $this->getJson('/.well-known/openid-configuration')
            ->assertOk()
            ->assertJsonPath('registration_endpoint', route('oauth.register'));
    }
}
