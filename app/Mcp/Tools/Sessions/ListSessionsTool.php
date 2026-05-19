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

#[Description('List game sessions in a campaign. Optionally filter by session type or public-only.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListSessionsTool extends Tool
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
            'campaign_id' => ['required', 'string'],
            'session_type' => ['nullable', 'string'],
            'public_only' => ['nullable', 'boolean'],
        ], [
            'campaign_id.required' => 'You must provide a campaign_id to list sessions.',
        ]);

        $query = array_filter([
            'campaign_id' => $validated['campaign_id'],
            'session_type' => $validated['session_type'] ?? null,
            'public_only' => isset($validated['public_only']) ? ($validated['public_only'] ? 'true' : 'false') : null,
        ], fn ($v) => $v !== null);

        try {
            $data = $this->client->get('/v1/sessions', $query);
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to list sessions for campaign '{$validated['campaign_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        $data['data'] = array_map(
            fn (array $item) => (new SessionData($item))->toArray(),
            $data['data'] ?? [],
        );

        return Response::structured($data ?: ['data' => []]);
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'campaign_id' => $schema->string()->description('The campaign ID to list sessions from.')->required(),
            'session_type' => $schema->string()->description('Filter by session type, e.g. audioUpload, playByPost, discordVoice.'),
            'public_only' => $schema->boolean()->description('When true, only return publicly visible sessions.'),
        ];
    }
}
