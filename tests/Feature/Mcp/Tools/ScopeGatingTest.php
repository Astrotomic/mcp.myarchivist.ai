<?php

namespace Tests\Feature\Mcp\Tools;

use App\Mcp\Tools\Beats\CreateBeatTool;
use App\Mcp\Tools\Beats\GetBeatTool;
use App\Mcp\Tools\Images\GenerateImageTool;
use App\Mcp\Tools\Images\GetImageUsageTool;
use App\Mcp\Tools\Links\CreateLinkTool;
use App\Services\ArchivistClient;
use App\Services\AuthContext;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * Verifies that Tool::shouldRegister() gates write tools by the caller's
 * OAuth scopes as returned by GET /v1/users/me. Laravel MCP calls this hook
 * before both `tools/list` and `tools/call`, so a false return here hides
 * the tool from listing and makes it "not found" if called anyway.
 */
final class ScopeGatingTest extends FeatureTestCase
{
    private const BASE_URL = 'https://api.myarchivist.test';

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.archivist.base_url', self::BASE_URL);

        $this->app->instance(
            ArchivistClient::class,
            new ArchivistClient(token: 'test-token'),
        );
    }

    #[Test]
    public function read_tools_are_always_registered(): void
    {
        $this->fakeUsersMe(apiProfile: 'agent', scopes: []);

        $authContext = $this->app->make(AuthContext::class);

        $this->assertTrue((new GetBeatTool)->shouldRegister($authContext));
        $this->assertTrue((new GetImageUsageTool)->shouldRegister($authContext));
    }

    #[Test]
    public function write_tools_are_hidden_when_agent_client_lacks_agent_write_scope(): void
    {
        $this->fakeUsersMe(apiProfile: 'agent', scopes: ['agent_read']);

        $authContext = $this->app->make(AuthContext::class);

        $this->assertFalse((new CreateBeatTool)->shouldRegister($authContext));
        $this->assertFalse((new CreateLinkTool)->shouldRegister($authContext));
        $this->assertFalse((new GenerateImageTool)->shouldRegister($authContext));
    }

    #[Test]
    public function write_tools_are_registered_when_agent_client_has_agent_write_scope(): void
    {
        $this->fakeUsersMe(apiProfile: 'agent', scopes: ['agent_read', 'agent_write']);

        $authContext = $this->app->make(AuthContext::class);

        $this->assertTrue((new CreateBeatTool)->shouldRegister($authContext));
        $this->assertTrue((new CreateLinkTool)->shouldRegister($authContext));
        $this->assertTrue((new GenerateImageTool)->shouldRegister($authContext));
    }

    #[Test]
    public function write_tools_are_registered_for_developer_api_key_regardless_of_scopes(): void
    {
        // Developer credentials (API keys) don't carry scopes; the API view
        // treats them as unrestricted so the MCP shouldn't filter their tools.
        $this->fakeUsersMe(apiProfile: 'developer', scopes: []);

        $authContext = $this->app->make(AuthContext::class);

        $this->assertTrue((new CreateBeatTool)->shouldRegister($authContext));
        $this->assertTrue((new GenerateImageTool)->shouldRegister($authContext));
    }

    #[Test]
    public function write_tools_are_registered_when_users_me_lookup_fails(): void
    {
        // Fail closed on the API side, open on the MCP side: showing all
        // tools and letting the API return a clean 403 is better UX than
        // silently hiding tools due to an unrelated infrastructure blip.
        Http::fake([
            self::BASE_URL.'/v1/users/me' => Http::response(['detail' => 'Server error'], 500),
        ]);

        $authContext = $this->app->make(AuthContext::class);

        $this->assertTrue((new CreateBeatTool)->shouldRegister($authContext));
    }

    #[Test]
    public function users_me_is_fetched_only_once_per_request(): void
    {
        $this->fakeUsersMe(apiProfile: 'agent', scopes: ['agent_read', 'agent_write']);

        $authContext = $this->app->make(AuthContext::class);

        // Simulate the framework asking every write tool whether to register.
        (new CreateBeatTool)->shouldRegister($authContext);
        (new CreateLinkTool)->shouldRegister($authContext);
        (new GenerateImageTool)->shouldRegister($authContext);
        $authContext->canWrite();
        $authContext->scopes();

        Http::assertSentCount(1);
    }

    /**
     * @param  array<int, string>  $scopes
     */
    private function fakeUsersMe(string $apiProfile, array $scopes): void
    {
        Http::fake([
            self::BASE_URL.'/v1/users/me' => Http::response([
                'id' => 'user_1',
                'name' => 'Test User',
                'givenName' => 'Test',
                'familyName' => 'User',
                'email' => 'test@example.test',
                'role' => 'user',
                'verified' => true,
                'newUser' => false,
                'createdAt' => '2024-01-01T00:00:00Z',
                'scopes' => $scopes,
                'api_profile' => $apiProfile,
            ], 200),
        ]);
    }
}
