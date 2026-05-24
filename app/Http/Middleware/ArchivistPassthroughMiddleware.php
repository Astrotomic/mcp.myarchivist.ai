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
            $scopes = implode(' ', config()->array('services.archivist.oauth_scopes_supported'));
            $resourceMetadata = route('well-known.oauth-protected-resource');

            return response()->json([
                'message' => 'Unauthenticated.',
            ], Response::HTTP_UNAUTHORIZED, [
                'WWW-Authenticate' => sprintf(
                    'Bearer resource_metadata="%s", scope="%s"',
                    $resourceMetadata,
                    $scopes
                ),
            ]);
        }

        return $next($request);
    }
}
