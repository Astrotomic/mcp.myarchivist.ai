<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class McpCorsMiddleware
{
    /**
     * @return array<string, string>
     */
    private function corsHeaders(): array
    {
        return [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, Accept, MCP-Protocol-Version',
            'Access-Control-Max-Age' => '86400',
        ];
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        foreach ($this->corsHeaders() as $header => $value) {
            $response->headers->set($header, $value);
        }

        return $response;
    }
}
