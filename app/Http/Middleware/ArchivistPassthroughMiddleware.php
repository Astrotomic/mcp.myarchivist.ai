<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArchivistPassthroughMiddleware
{
    /**
     * MCP methods that expose only server metadata and may be called
     * without a Bearer token (e.g. ChatGPT submission tool scans).
     *
     * @var list<string>
     */
    private const DISCOVERY_METHODS = [
        'initialize',
        'tools/list',
        'notifications/initialized',
    ];

    /**
     * Extract the Bearer token from the Authorization header and store it
     * in the request so that ArchivistClient can forward it as x-api-key.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! empty($request->bearerToken())) {
            return $next($request);
        }

        $method = $this->resolveJsonRpcMethod($request);

        if ($method !== null && in_array($method, self::DISCOVERY_METHODS, true)) {
            return $next($request);
        }

        return $this->unauthenticatedResponse($request);
    }

    private function resolveJsonRpcMethod(Request $request): ?string
    {
        /** @var array<string, mixed> $payload */
        $payload = $request->json()->all();

        $method = $payload['method'] ?? null;

        return is_string($method) && $method !== '' ? $method : null;
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
