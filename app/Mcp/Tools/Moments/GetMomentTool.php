<?php

namespace App\Mcp\Tools\Moments;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\MomentData;
use App\Mcp\Tools\Concerns\HasArchivistOutputSchema;
use App\Services\ArchivistClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get a specific moment by ID.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetMomentTool extends Tool
{
    use HasArchivistOutputSchema;

    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'moment_id' => ['required', 'string'],
        ], [
            'moment_id.required' => 'You must provide a moment_id to retrieve a specific moment.',
        ]);

        try {
            $data = $this->client->get("/v1/moments/{$validated['moment_id']}");
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to get moment '{$validated['moment_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        return Response::structured((new MomentData($data))->toArray());
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'moment_id' => $schema->string()->description('The ID of the moment to retrieve.')->required(),
        ];
    }
}
