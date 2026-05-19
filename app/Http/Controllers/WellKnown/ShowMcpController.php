<?php

namespace App\Http\Controllers\WellKnown;

use App\Mcp\Servers\ArchivistServer;
use Illuminate\Http\JsonResponse;

class ShowMcpController
{
    public function __invoke(): JsonResponse
    {
        $server = app(ArchivistServer::class);
        $context = $server->createContext();

        return response()->json([
            'mcp_version' => '2025-03-26',
            'server' => [
                'name' => $context->serverName,
                'version' => $context->serverVersion,
                'description' => $context->instructions,
            ],
            'endpoints' => [
                [
                    'url' => url('/mcp'),
                    'transport' => 'streamable-http',
                ],
            ],
            'authentication' => [
                'required' => true,
                'schemes' => ['oauth2', 'bearer'],
            ],
            'documentation' => 'https://developers.myarchivist.ai',
            'server_card' => route('well-known.mcp.server-card'),
        ]);
    }
}
