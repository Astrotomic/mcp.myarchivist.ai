<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/**
 * RFC 7591 dynamic client registration proxy.
 *
 * ChatGPT validates DCR on the MCP server origin, so we expose /oauth/register
 * here and forward registration requests to the Archivist app authorization server.
 */
class RegisterOauthClientController
{
    /** @var array<string, string> */
    private const CORS_HEADERS = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'POST, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Accept',
        'Cache-Control' => 'no-store',
    ];

    public function __invoke(Request $request): Response
    {
        if ($request->isMethod('OPTIONS')) {
            return response('', Response::HTTP_NO_CONTENT, self::CORS_HEADERS);
        }

        $payload = $request->json()?->all() ?? [];
        if ($payload === [] && is_string($request->getContent()) && $request->getContent() !== '') {
            $decoded = json_decode($request->getContent(), true);
            $payload = is_array($decoded) ? $decoded : [];
        }

        $upstream = Http::acceptJson()
            ->asJson()
            ->timeout(15)
            ->post(URL::toApp('/api/oauth/register'), $payload);

        $body = $upstream->json();
        if (! is_array($body)) {
            $body = ['error' => 'server_error'];
        }

        return response()->json($body, $upstream->status(), self::CORS_HEADERS);
    }
}
