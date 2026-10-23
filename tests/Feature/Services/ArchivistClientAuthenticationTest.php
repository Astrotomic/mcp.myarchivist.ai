<?php

namespace Tests\Feature\Services;

use App\Services\ArchivistClient;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class ArchivistClientAuthenticationTest extends FeatureTestCase
{
    #[Test]
    public function it_forwards_api_keys_in_the_api_key_header(): void
    {
        Http::fake(['*' => Http::response([])]);

        (new ArchivistClient(token: 'api-key', usesApiKey: true))->get('/v1/users/me');

        Http::assertSent(fn (Request $request): bool => $request->hasHeader('x-api-key', 'api-key')
            && ! $request->hasHeader('Authorization'));
    }

    #[Test]
    public function it_forwards_oauth_tokens_as_bearer_credentials(): void
    {
        Http::fake(['*' => Http::response([])]);

        (new ArchivistClient(token: 'oauth-token', usesApiKey: false))->get('/v1/users/me');

        Http::assertSent(fn (Request $request): bool => $request->hasHeader('Authorization', 'Bearer oauth-token')
            && ! $request->hasHeader('x-api-key'));
    }
}
