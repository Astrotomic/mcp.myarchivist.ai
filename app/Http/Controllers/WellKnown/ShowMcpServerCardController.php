<?php

namespace App\Http\Controllers\WellKnown;

use App\Mcp\Servers\ArchivistServer;
use Illuminate\Http\JsonResponse;
use Laravel\Mcp\Server\Prompt;
use Laravel\Mcp\Server\Resource;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Transport\FakeTransporter;

class ShowMcpServerCardController
{
    public function __invoke(): JsonResponse
    {
        $server = new ArchivistServer(new FakeTransporter);
        $context = $server->createContext();
        $tools = collect($context->tools())->map(fn (string|Tool $class): Tool => $class instanceof Tool ? $class : app($class));
        $resources = collect($context->resources())->map(fn (string|Resource $class): Resource => $class instanceof Resource ? $class : app($class));
        $prompts = collect($context->prompts())->map(fn (string|Prompt $class): Prompt => $class instanceof Prompt ? $class : app($class));

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
