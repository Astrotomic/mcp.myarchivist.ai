<?php

namespace App\Http\Controllers;

use App\Mcp\OAuthChallenge;
use Illuminate\Http\Client\ConnectionException;
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

        $payload = $request->json()->all();
        $content = $request->getContent();
        if ($payload === [] && $content !== '') {
            $decoded = json_decode($content, true);
            $payload = is_array($decoded) ? $decoded : [];
        }

        try {
            $upstream = Http::acceptJson()
                ->asJson()
                ->connectTimeout(3)
                ->timeout(10)
                ->post(URL::toApp('/api/oauth/register'), $payload);
        } catch (ConnectionException $exception) {
            report($exception);

            return response()->json([
                'error' => 'temporarily_unavailable',
                'error_description' => 'Archivist AI could not complete client registration. Try connecting again. Setup: '.OAuthChallenge::CONNECT_URL,
            ], Response::HTTP_SERVICE_UNAVAILABLE, self::CORS_HEADERS);
        }

        $body = $upstream->json();
        if (! is_array($body)) {
            $body = ['error' => 'server_error'];
        } elseif ($upstream->successful()) {
            $body['client_id_issued_at'] = $body['client_id_issued_at'] ?? time();

            if (! isset($body['scope']) && isset($body['scopes_supported']) && is_array($body['scopes_supported'])) {
                $scopes = array_values(array_filter(
                    $body['scopes_supported'],
                    static fn (mixed $scope): bool => is_string($scope) && $scope !== '',
                ));
                if ($scopes !== []) {
                    $body['scope'] = implode(' ', $scopes);
                }
            }
        }

        return response()->json($body, $upstream->status(), self::CORS_HEADERS);
    }
}
