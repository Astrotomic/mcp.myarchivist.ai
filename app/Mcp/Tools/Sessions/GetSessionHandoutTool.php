<?php

namespace App\Mcp\Tools\Sessions;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\SessionHandoutData;
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

#[Description('Get the generated session handout for a game session, including summary, outlines, spotlights, and notable moments.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetSessionHandoutTool extends Tool
{
    use HasArchivistOutputSchema;

    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'session_id' => ['required', 'string'],
        ], [
            'session_id.required' => 'You must provide a session_id to retrieve a session handout.',
        ]);

        try {
            $data = $this->client->get("/v1/sessions/{$validated['session_id']}/handout");
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to get handout for session '{$validated['session_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        return Response::structured((new SessionHandoutData($data))->toArray());
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'session_id' => $schema->string()->description('The ID of the session to retrieve the generated handout for.')->required(),
        ];
    }
}
