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
}
