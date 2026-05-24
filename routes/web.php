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

Route::prefix('.well-known')->name('well-known.')->middleware(WellKnownHeadersMiddleware::class)->group(function (): void {
    // MCP Discovery
    Route::get('/mcp', ShowMcpController::class)->name('mcp');
    Route::get('/mcp/server-card.json', ShowMcpServerCardController::class)->name('mcp.server-card');
    // oAuth
    Route::get('/oauth-authorization-server', ShowOauthAuthorizationServerController::class)->name('oauth-authorization-server');
    Route::get('/oauth-protected-resource', ShowOauthProtectedResourceController::class)->name('oauth-protected-resource');
    // OpenAI
    Route::get('/openai-apps-challenge', ShowOpenaiAppsChallengeController::class)->name('openai-apps-challenge');
});
