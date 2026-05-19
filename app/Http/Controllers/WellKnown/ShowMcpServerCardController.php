<?php

namespace App\Http\Controllers\WellKnown;

use App\Mcp\Servers\ArchivistServer;
use Illuminate\Http\JsonResponse;
use Laravel\Mcp\Server\Prompt;
use Laravel\Mcp\Server\Resource;
use Laravel\Mcp\Server\Tool;

class ShowMcpServerCardController
{
    public function __invoke(): JsonResponse
    {
        $server = app(ArchivistServer::class);
        $context = $server->createContext();
        $tools = collect($context->tools())->map(fn (string $class): Tool => app($class));
        $resources = collect($context->resources())->map(fn (string $class): Resource => app($class));
        $prompts = collect($context->prompts())->map(fn (string $class): Prompt => app($class));

        return response()->json([
            'serverInfo' => [
                'name' => $context->serverName,
                'version' => $context->serverVersion,
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
