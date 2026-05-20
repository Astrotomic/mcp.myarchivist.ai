<?php

namespace App\Http\Controllers\WellKnown;

use App\Mcp\Servers\ArchivistServer;
use Illuminate\Http\JsonResponse;
use Laravel\Mcp\Server\Transport\FakeTransporter;

class ShowMcpServerCardController
{
    public function __invoke(): JsonResponse
    {
        $server = new ArchivistServer(new FakeTransporter);
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
