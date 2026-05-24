<?php

namespace App\Http\Controllers\WellKnown;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;

class ShowOauthAuthorizationServerController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'issuer' => URL::toApp('/'),
            'authorization_endpoint' => URL::toApp('/oauth/authorize'),
            'token_endpoint' => URL::toApp('/api/oauth/token'),
            'revocation_endpoint' => URL::toApp('/api/oauth/revoke'),
            'registration_endpoint' => route('oauth.register'),
            'response_types_supported' => ['code'],
            'grant_types_supported' => ['authorization_code', 'refresh_token'],
            'code_challenge_methods_supported' => ['S256'],
            'token_endpoint_auth_methods_supported' => ['none'],
            'scopes_supported' => config()->array('services.archivist.oauth_scopes_supported'),
        ]);
    }
}
