<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/.well-known/oauth-authorization-server', function () {
    $appUrl = rtrim((string) config('services.archivist.app_url'), '/');

    return response()->json([
        'issuer' => $appUrl,
        'authorization_endpoint' => "{$appUrl}/oauth/authorize",
        'token_endpoint' => "{$appUrl}/api/oauth/token",
        'revocation_endpoint' => "{$appUrl}/api/oauth/revoke",
        'response_types_supported' => ['code'],
        'grant_types_supported' => ['authorization_code', 'refresh_token'],
        'code_challenge_methods_supported' => ['S256'],
        'token_endpoint_auth_methods_supported' => ['none'],
        'scopes_supported' => [
            'profile',
            'worlds_read',
            'sessions_read',
            'characters_read',
        ],
    ])->withHeaders([
        'Access-Control-Allow-Origin' => '*',
        'Cache-Control' => 'public, max-age=3600',
    ]);
});

Route::get('/.well-known/openai-apps-challenge', function () {
    $token = (string) config('services.openai.apps_challenge_token', '');

    abort_if($token === '', 404);

    return response($token, 200)
        ->header('Content-Type', 'text/plain; charset=utf-8')
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Cache-Control', 'no-store');
});

Route::get('/.well-known/oauth-protected-resource', function () {
    $appUrl = rtrim((string) config('services.archivist.app_url'), '/');
    $mcpUrl = rtrim((string) config('app.url'), '/');

    return response()->json([
        'resource' => $mcpUrl,
        'authorization_servers' => [$appUrl],
        'scopes_supported' => [
            'profile',
            'worlds_read',
            'sessions_read',
            'characters_read',
        ],
        'bearer_methods_supported' => ['header'],
    ])->withHeaders([
        'Access-Control-Allow-Origin' => '*',
        'Cache-Control' => 'public, max-age=3600',
    ]);
});
