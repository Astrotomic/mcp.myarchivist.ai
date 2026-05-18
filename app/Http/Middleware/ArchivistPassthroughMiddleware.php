<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
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
            throw new AuthenticationException('Unauthenticated.');
        }

        return $next($request);
    }
}
