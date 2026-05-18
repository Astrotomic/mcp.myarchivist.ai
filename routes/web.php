<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/', function (): JsonResponse {
    $mcpUrl = rtrim((string) config('app.url'), '/');
    $appUrl = rtrim((string) config('services.archivist.app_url'), '/');

    return response()->json([
        'name' => 'Archivist AI MCP Server',
        'description' => 'Model Context Protocol server for Archivist AI — the TTRPG campaign memory platform. Provides read-only access to campaigns, sessions, characters, locations, factions, items, quests, journals, and more.',
        'version' => '1.0.0',
        'transport' => [
            'type' => 'streamable-http',
            'url' => "{$mcpUrl}/mcp",
        ],
        'authentication' => [
            'type' => 'oauth2',
            'authorization_server' => "{$mcpUrl}/.well-known/oauth-authorization-server",
            'protected_resource' => "{$mcpUrl}/.well-known/oauth-protected-resource",
        ],
        'documentation' => 'https://developers.myarchivist.ai',
        'product' => 'https://www.myarchivist.ai',
        'links' => [
            'server_card' => "{$mcpUrl}/.well-known/mcp/server-card.json",
            'openapi' => 'https://api.myarchivist.ai/openapi.json',
            'llms_txt' => 'https://www.myarchivist.ai/llms.txt',
        ],
    ])->withHeaders([
        'Access-Control-Allow-Origin' => '*',
        'Cache-Control' => 'public, max-age=3600',
    ]);
});

/*
|--------------------------------------------------------------------------
| MCP Discovery & Server Card
|--------------------------------------------------------------------------
*/

Route::get('/.well-known/mcp', function (): JsonResponse {
    $mcpUrl = rtrim((string) config('app.url'), '/');

    return response()->json([
        'mcp_version' => '2025-03-26',
        'server' => [
            'name' => 'Archivist AI MCP Server',
            'version' => '1.0.0',
            'description' => 'Read-only access to Archivist AI TTRPG campaign data: campaigns, sessions, characters, locations, factions, items, quests, journals, and entity links.',
        ],
        'endpoints' => [
            [
                'url' => "{$mcpUrl}/mcp",
                'transport' => 'streamable-http',
            ],
        ],
        'authentication' => [
            'required' => true,
            'schemes' => ['oauth2', 'bearer'],
        ],
        'documentation' => 'https://developers.myarchivist.ai',
        'server_card' => "{$mcpUrl}/.well-known/mcp/server-card.json",
    ])->withHeaders([
        'Access-Control-Allow-Origin' => '*',
        'Cache-Control' => 'public, max-age=3600',
    ]);
});

Route::get('/.well-known/mcp/server-card.json', function (): JsonResponse {
    return response()->json([
        'serverInfo' => [
            'name' => 'Archivist AI MCP Server',
            'version' => '1.0.0',
        ],
        'authentication' => [
            'required' => true,
            'schemes' => ['oauth2'],
        ],
        'tools' => [
            ['name' => 'list_campaigns', 'description' => 'List your MyArchivist campaigns. Returns a paginated list of campaigns.', 'inputSchema' => ['type' => 'object', 'properties' => ['page' => ['type' => 'integer', 'description' => 'Page number (default: 1).'], 'size' => ['type' => 'integer', 'description' => 'Page size (default: 20).']]]],
            ['name' => 'get_campaign', 'description' => 'Get a specific MyArchivist campaign by its ID.', 'inputSchema' => ['type' => 'object', 'properties' => ['campaign_id' => ['type' => 'string', 'description' => 'Campaign UUID.']], 'required' => ['campaign_id']]],
            ['name' => 'get_campaign_stats', 'description' => 'Get statistics for a specific campaign: character count, session count, and more.', 'inputSchema' => ['type' => 'object', 'properties' => ['campaign_id' => ['type' => 'string', 'description' => 'Campaign UUID.']], 'required' => ['campaign_id']]],
            ['name' => 'list_characters', 'description' => 'List characters in a campaign. Optionally filter by name search, character type, and approval status.', 'inputSchema' => ['type' => 'object', 'properties' => ['campaign_id' => ['type' => 'string'], 'page' => ['type' => 'integer'], 'size' => ['type' => 'integer']], 'required' => ['campaign_id']]],
            ['name' => 'get_character', 'description' => 'Get a specific character by ID including aliases, backstory, and speaker linkage.', 'inputSchema' => ['type' => 'object', 'properties' => ['character_id' => ['type' => 'string']], 'required' => ['character_id']]],
            ['name' => 'list_sessions', 'description' => 'List game sessions in a campaign. Optionally filter by session type or public-only.', 'inputSchema' => ['type' => 'object', 'properties' => ['campaign_id' => ['type' => 'string'], 'page' => ['type' => 'integer'], 'size' => ['type' => 'integer']], 'required' => ['campaign_id']]],
            ['name' => 'get_session', 'description' => 'Get a specific game session by ID. Optionally include related beats and moments.', 'inputSchema' => ['type' => 'object', 'properties' => ['session_id' => ['type' => 'string']], 'required' => ['session_id']]],
            ['name' => 'get_session_cast_analysis', 'description' => 'Get the cast analysis for a game session, including talk-share breakdown and core session metrics.', 'inputSchema' => ['type' => 'object', 'properties' => ['session_id' => ['type' => 'string']], 'required' => ['session_id']]],
            ['name' => 'list_beats', 'description' => 'List beats in a campaign, ordered by index. Beats represent story moments (major, minor, step).', 'inputSchema' => ['type' => 'object', 'properties' => ['campaign_id' => ['type' => 'string'], 'page' => ['type' => 'integer'], 'size' => ['type' => 'integer']], 'required' => ['campaign_id']]],
            ['name' => 'get_beat', 'description' => 'Get a specific beat by ID.', 'inputSchema' => ['type' => 'object', 'properties' => ['beat_id' => ['type' => 'string']], 'required' => ['beat_id']]],
            ['name' => 'list_moments', 'description' => 'List moments in a campaign or session. Moments capture memorable quotes and events.', 'inputSchema' => ['type' => 'object', 'properties' => ['campaign_id' => ['type' => 'string'], 'page' => ['type' => 'integer'], 'size' => ['type' => 'integer']], 'required' => ['campaign_id']]],
            ['name' => 'get_moment', 'description' => 'Get a specific moment by ID.', 'inputSchema' => ['type' => 'object', 'properties' => ['moment_id' => ['type' => 'string']], 'required' => ['moment_id']]],
            ['name' => 'list_factions', 'description' => 'List factions in a campaign. Factions represent guilds, organisations, or other groups.', 'inputSchema' => ['type' => 'object', 'properties' => ['campaign_id' => ['type' => 'string'], 'page' => ['type' => 'integer'], 'size' => ['type' => 'integer']], 'required' => ['campaign_id']]],
            ['name' => 'get_faction', 'description' => 'Get a specific faction by ID.', 'inputSchema' => ['type' => 'object', 'properties' => ['faction_id' => ['type' => 'string']], 'required' => ['faction_id']]],
            ['name' => 'list_locations', 'description' => 'List locations in a campaign. Locations can be nested (cities, taverns, dungeons, etc.).', 'inputSchema' => ['type' => 'object', 'properties' => ['campaign_id' => ['type' => 'string'], 'page' => ['type' => 'integer'], 'size' => ['type' => 'integer']], 'required' => ['campaign_id']]],
            ['name' => 'get_location', 'description' => 'Get a specific location by ID.', 'inputSchema' => ['type' => 'object', 'properties' => ['location_id' => ['type' => 'string']], 'required' => ['location_id']]],
            ['name' => 'list_items', 'description' => 'List items in a campaign. Items include weapons, armour, artefacts, and other notable objects.', 'inputSchema' => ['type' => 'object', 'properties' => ['campaign_id' => ['type' => 'string'], 'page' => ['type' => 'integer'], 'size' => ['type' => 'integer']], 'required' => ['campaign_id']]],
            ['name' => 'get_item', 'description' => 'Get a specific item by ID.', 'inputSchema' => ['type' => 'object', 'properties' => ['item_id' => ['type' => 'string']], 'required' => ['item_id']]],
            ['name' => 'list_quests', 'description' => 'List quests in a campaign with pagination. Filter by status (planned, in-progress, blocked, failed, done, n/a) or category (main, side, faction, personal, n/a).', 'inputSchema' => ['type' => 'object', 'properties' => ['campaign_id' => ['type' => 'string'], 'page' => ['type' => 'integer'], 'size' => ['type' => 'integer']], 'required' => ['campaign_id']]],
            ['name' => 'get_quest', 'description' => 'Get a fully expanded quest by ID, including objectives, progress log, related entity refs, and session provenance.', 'inputSchema' => ['type' => 'object', 'properties' => ['quest_id' => ['type' => 'string']], 'required' => ['quest_id']]],
            ['name' => 'list_journals', 'description' => 'List journal entries in a campaign. Results are filtered to entries the caller can see. Content is omitted from the list; use get_journal to fetch full content.', 'inputSchema' => ['type' => 'object', 'properties' => ['campaign_id' => ['type' => 'string'], 'page' => ['type' => 'integer'], 'size' => ['type' => 'integer']], 'required' => ['campaign_id']]],
            ['name' => 'get_journal', 'description' => "Get a specific journal entry by ID including full content and the caller's effective permission level.", 'inputSchema' => ['type' => 'object', 'properties' => ['journal_id' => ['type' => 'string']], 'required' => ['journal_id']]],
            ['name' => 'list_journal_folders', 'description' => 'List journal folders for a campaign. Folders are ordered by path and position for tree rendering.', 'inputSchema' => ['type' => 'object', 'properties' => ['campaign_id' => ['type' => 'string']], 'required' => ['campaign_id']]],
            ['name' => 'get_journal_folder', 'description' => 'Get a specific journal folder by ID.', 'inputSchema' => ['type' => 'object', 'properties' => ['folder_id' => ['type' => 'string']], 'required' => ['folder_id']]],
            ['name' => 'list_links', 'description' => 'List links between entities in a campaign. Supports filtering by source/target entity and relationship alias.', 'inputSchema' => ['type' => 'object', 'properties' => ['campaign_id' => ['type' => 'string'], 'page' => ['type' => 'integer'], 'size' => ['type' => 'integer']], 'required' => ['campaign_id']]],
        ],
        'resources' => [],
        'prompts' => [],
    ])->withHeaders([
        'Access-Control-Allow-Origin' => '*',
        'Cache-Control' => 'public, max-age=86400',
    ]);
});

/*
|--------------------------------------------------------------------------
| OAuth & OpenAI Discovery
|--------------------------------------------------------------------------
*/

Route::get('/.well-known/oauth-authorization-server', function (): JsonResponse {
    $appUrl = rtrim((string) config('services.archivist.app_url'), '/');
    $mcpUrl = rtrim((string) config('app.url'), '/');

    return response()->json([
        'issuer' => $appUrl,
        'authorization_endpoint' => "{$appUrl}/oauth/authorize",
        'token_endpoint' => "{$appUrl}/api/oauth/token",
        'revocation_endpoint' => "{$appUrl}/api/oauth/revoke",
        'registration_endpoint' => "{$mcpUrl}/oauth/register",
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

Route::get('/.well-known/openai-apps-challenge', function (): Response {
    $token = (string) config('services.openai.apps_challenge_token', '');

    abort_if($token === '', 404);

    return response($token, 200)
        ->header('Content-Type', 'text/plain; charset=utf-8')
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Cache-Control', 'no-store');
});

Route::get('/.well-known/oauth-protected-resource', function (): JsonResponse {
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

Route::post('/oauth/register', function (Request $request): JsonResponse {
    // RFC 7591 dynamic client registration
    // We return a static public client since this MCP server has one known client type
    return response()->json([
        'client_id' => config('services.archivist.mcp_client_id'),
        'client_id_issued_at' => time(),
        'redirect_uris' => $request->input('redirect_uris', []),
        'grant_types' => ['authorization_code', 'refresh_token'],
        'response_types' => ['code'],
        'token_endpoint_auth_method' => 'none',
        'scope' => 'profile worlds_read sessions_read characters_read',
    ], 201)->withHeaders([
        'Access-Control-Allow-Origin' => '*',
    ]);
});
