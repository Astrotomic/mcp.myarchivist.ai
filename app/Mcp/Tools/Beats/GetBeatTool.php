<?php

namespace App\Mcp\Tools\Beats;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\BeatData;
use App\Services\ArchivistClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get a specific beat by ID.')]
#[IsReadOnly]
#[IsIdempotent]
class GetBeatTool extends Tool
{
    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'beat_id' => ['required', 'string'],
        ], [
            'beat_id.required' => 'You must provide a beat_id to retrieve a specific beat.',
        ]);

        try {
            $data = $this->client->get("/v1/beats/{$validated['beat_id']}");
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to get beat '{$validated['beat_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        return Response::structured((new BeatData($data))->toArray());
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'beat_id' => $schema->string()->description('The ID of the beat to retrieve.')->required(),
        ];
    }
}
