<?php

namespace App\Http\Controllers\WellKnown;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;

class ShowOauthProtectedResourceController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'resource' => url('/mcp'),
            'authorization_servers' => [rtrim(URL::toApp('/'), '/')],
            'scopes_supported' => config()->array('services.archivist.oauth_scopes_supported'),
            'bearer_methods_supported' => ['header'],
        ]);
    }
}
