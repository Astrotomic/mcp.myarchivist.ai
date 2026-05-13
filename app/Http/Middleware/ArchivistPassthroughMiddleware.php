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
        $token = $request->bearerToken();

        if ($token !== null) {
            $request->attributes->set('archivist_api_key', $token);
        }

        return $next($request);
    }
}
