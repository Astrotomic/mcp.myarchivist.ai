<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * RFC 7591 dynamic client registration
 * We return a static public client since this MCP server has one known client type
 */
class RegisterOauthClientController
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'client_id' => config('services.archivist.mcp_client_id'),
            'client_id_issued_at' => time(),
            'redirect_uris' => $request->input('redirect_uris', []),
            'grant_types' => ['authorization_code', 'refresh_token'],
            'response_types' => ['code'],
            'token_endpoint_auth_method' => 'none',
            'scope' => 'profile worlds_read sessions_read characters_read',
        ], 201);
    }
}
