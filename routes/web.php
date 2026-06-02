<?php

use App\Http\Controllers\RegisterOauthClientController;
use App\Http\Controllers\WellKnown\ShowMcpController;
use App\Http\Controllers\WellKnown\ShowMcpServerCardController;
use App\Http\Controllers\WellKnown\ShowOauthAuthorizationServerController;
use App\Http\Controllers\WellKnown\ShowOauthProtectedResourceController;
use App\Http\Controllers\WellKnown\ShowOpenaiAppsChallengeController;
use App\Http\Middleware\WellKnownHeadersMiddleware;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/mcp');

Route::match(['POST', 'OPTIONS'], '/oauth/register', RegisterOauthClientController::class)->name('oauth.register');
// Alias for clients that assume the app.myarchivist.ai /api/oauth/register path shape.
Route::match(['POST', 'OPTIONS'], '/api/oauth/register', RegisterOauthClientController::class);

Route::prefix('mcp/.well-known')->middleware(WellKnownHeadersMiddleware::class)->group(function (): void {
    Route::get('/oauth-protected-resource', ShowOauthProtectedResourceController::class);
    Route::get('/oauth-authorization-server', ShowOauthAuthorizationServerController::class);
    Route::get('/openid-configuration', ShowOauthAuthorizationServerController::class);
});

Route::prefix('.well-known')->name('well-known.')->middleware(WellKnownHeadersMiddleware::class)->group(function (): void {
    // MCP Discovery
    Route::get('/mcp', ShowMcpController::class)->name('mcp');
    Route::get('/mcp/server-card.json', ShowMcpServerCardController::class)->name('mcp.server-card');
    // OAuth / OIDC discovery (RFC 9728 path suffix + RFC 8414 fallbacks)
    Route::get('/oauth-authorization-server', ShowOauthAuthorizationServerController::class)->name('oauth-authorization-server');
    Route::get('/oauth-authorization-server/{path}', ShowOauthAuthorizationServerController::class)->where('path', '.+');
    Route::get('/openid-configuration', ShowOauthAuthorizationServerController::class)->name('openid-configuration');
    Route::get('/openid-configuration/{path}', ShowOauthAuthorizationServerController::class)->where('path', '.+');
    Route::get('/oauth-protected-resource', ShowOauthProtectedResourceController::class)->name('oauth-protected-resource');
    // OpenAI
    Route::get('/openai-apps-challenge', ShowOpenaiAppsChallengeController::class)->name('openai-apps-challenge');
});

// Laravel MCP AddWwwAuthenticateHeader enables resource_metadata when this route name exists.
Route::get('/.well-known/oauth-protected-resource/{path}', ShowOauthProtectedResourceController::class)
    ->where('path', '.+')
    ->middleware(WellKnownHeadersMiddleware::class)
    ->name('mcp.oauth.protected-resource.nested');
