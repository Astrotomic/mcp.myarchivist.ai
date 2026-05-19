<?php

namespace App\Http\Controllers\WellKnown;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;

class ShowOauthProtectedResourceController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'resource' => url('/'),
            'authorization_servers' => [URL::toApp('/')],
            'scopes_supported' => config('services.archivist.oauth_scopes_supported'),
            'bearer_methods_supported' => ['header'],
        ]);
    }
}
