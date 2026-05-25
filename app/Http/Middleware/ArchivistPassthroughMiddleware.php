<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArchivistPassthroughMiddleware
{
    /**
     * Extract the Bearer token from the Authorization header and store it
     * in the request so that ArchivistClient can forward it as x-api-key.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (empty($request->bearerToken())) {
            $resourceMetadata = url('/.well-known/oauth-protected-resource/mcp');

            return response()->json([
                'message' => 'Unauthenticated.',
            ], Response::HTTP_UNAUTHORIZED, [
                'WWW-Authenticate' => sprintf(
                    'Bearer realm="mcp", resource_metadata="%s", error="invalid_token"',
                    $resourceMetadata,
                ),
            ]);
        }

        return $next($request);
    }
}
