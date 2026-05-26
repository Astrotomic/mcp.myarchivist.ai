<?php

namespace Tests\Feature\Http\Controllers\WellKnown;

use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class ShowOauthProtectedResourceControllerTest extends FeatureTestCase
{
    #[Test]
    public function it_serves_protected_resource_metadata_at_the_root_well_known_path(): void
    {
        $this->getJson('/.well-known/oauth-protected-resource')
            ->assertOk()
            ->assertJson(function (AssertableJson $json): void {
                $json
                    ->where('resource', url('/mcp'))
                    ->where('authorization_servers', ['https://app.myarchivist.ai'])
                    ->etc();
            });
    }

    #[Test]
    public function it_serves_protected_resource_metadata_at_the_rfc_9728_path_suffix(): void
    {
        $this->getJson('/.well-known/oauth-protected-resource/mcp')
            ->assertOk()
            ->assertJsonPath('resource', url('/mcp'));
    }

    #[Test]
    public function it_serves_protected_resource_metadata_at_the_legacy_mcp_prefixed_path(): void
    {
        $this->getJson('/mcp/.well-known/oauth-protected-resource')
            ->assertOk()
            ->assertJsonPath('resource', url('/mcp'));
    }
}
