<?php

namespace App\Http\Controllers\WellKnown;

use App\Mcp\Servers\ArchivistServer;
use Illuminate\Http\JsonResponse;

class ShowMcpServerCardController
{
    public function __invoke(): JsonResponse
    {
        $server = ArchivistServer::fake();
        $context = $server->createContext();
        $tools = $context->tools();
        $resources = $context->resources();
        $prompts = $context->prompts();

        return response()->json([
            'serverInfo' => [
                'name' => $context->implementation->name,
                'version' => $context->implementation->version,
            ],
            'authentication' => [
                'required' => true,
                'schemes' => ['oauth2'],
            ],
            'tools' => $tools->toArray(),
            'resources' => $resources->toArray(),
            'prompts' => $prompts->toArray(),
        ]);
    }
}
