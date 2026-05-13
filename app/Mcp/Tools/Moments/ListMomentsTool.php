<?php

namespace App\Mcp\Tools\Moments;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\MomentData;
use App\Services\ArchivistClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List moments in a campaign or session. Moments capture memorable quotes and events.')]
#[IsReadOnly]
#[IsIdempotent]
class ListMomentsTool extends Tool
{
    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'campaign_id' => ['nullable', 'string'],
            'session_id' => ['nullable', 'string'],
        ]);

        $query = array_filter([
            'campaign_id' => $validated['campaign_id'] ?? null,
            'session_id' => $validated['session_id'] ?? null,
        ], fn ($v) => $v !== null);

        try {
            $data = $this->client->get('/v1/moments', $query);
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to list moments from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        $data['data'] = array_map(
            fn (array $item) => (new MomentData($item))->toArray(),
            $data['data'] ?? [],
        );

        return Response::structured($data ?: ['data' => []]);
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'campaign_id' => $schema->string()->description('Filter moments by campaign ID.'),
            'session_id' => $schema->string()->description('Filter moments by session ID.'),
        ];
    }
}
