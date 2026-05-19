<?php

namespace App\Mcp\Tools\Sessions;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\SessionData;
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

#[Description('Get a specific game session by ID. Optionally include related beats and moments.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetSessionTool extends Tool
{
    use HasArchivistOutputSchema;

    #[\Override]
    protected function outputDtoClass(): string
    {
        return SessionData::class;
    }

    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'session_id' => ['required', 'string'],
            'include_beats' => ['nullable', 'boolean'],
            'include_moments' => ['nullable', 'boolean'],
        ], [
            'session_id.required' => 'You must provide a session_id to retrieve a specific session.',
        ]);

        $query = array_filter([
            'include_beats' => isset($validated['include_beats']) ? ($validated['include_beats'] ? 'true' : 'false') : null,
            'include_moments' => isset($validated['include_moments']) ? ($validated['include_moments'] ? 'true' : 'false') : null,
        ], fn ($v) => $v !== null);

        try {
            $data = $this->client->get("/v1/sessions/{$validated['session_id']}", $query);
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to get session '{$validated['session_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        return $this->structuredResponse($data);
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'session_id' => $schema->string()->description('The ID of the session to retrieve.')->required(),
            'include_beats' => $schema->boolean()->description('When true, include related beats in the response.'),
            'include_moments' => $schema->boolean()->description('When true, include related moments in the response.'),
        ];
    }
}
