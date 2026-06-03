<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArchivistPassthroughMiddleware
{
    /**
     * All MCP JSON-RPC calls require a Bearer token. OAuth clients (e.g. mcp-remote /
     * Nexus) must complete browser sign-in so tokens are issued before connect.
     * Public tool metadata for store review lives at /.well-known/mcp/server-card.json.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! empty($request->bearerToken())) {
            return $next($request);
        }

        return $this->unauthenticatedResponse($request);
    }

    private function unauthenticatedResponse(Request $request): JsonResponse
    {
        return response()->json([
            'jsonrpc' => '2.0',
            'id' => $request->json('id'),
            'error' => [
                'code' => -32001,
                'message' => 'Unauthorized',
            ],
        ], Response::HTTP_UNAUTHORIZED)->withHeaders([
            'WWW-Authenticate' => $this->wwwAuthenticateHeader(),
        ]);
    }

    private function wwwAuthenticateHeader(): string
    {
        $resourceMetadata = route('mcp.oauth.protected-resource.nested', ['path' => 'mcp']);

        return sprintf('Bearer realm="mcp", resource_metadata="%s"', $resourceMetadata);
    }
}
