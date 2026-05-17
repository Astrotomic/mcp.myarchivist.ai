<?php

namespace App\Mcp\Tools\Beats;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\BeatData;
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

#[Description('List beats in a campaign, ordered by index. Beats represent story moments (major, minor, step).')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListBeatsTool extends Tool
{
    use HasArchivistOutputSchema;

    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'campaign_id' => ['required', 'string'],
            'page' => ['nullable', 'integer'],
            'size' => ['nullable', 'integer'],
        ], [
            'campaign_id.required' => 'You must provide a campaign_id to list beats.',
        ]);

        $query = array_filter([
            'campaign_id' => $validated['campaign_id'],
            'page' => $validated['page'] ?? null,
            'size' => $validated['size'] ?? null,
        ], fn ($v) => $v !== null);

        try {
            $data = $this->client->get('/v1/beats', $query);
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to list beats for campaign '{$validated['campaign_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        $data['data'] = array_map(
            fn (array $item) => (new BeatData($item))->toArray(),
            $data['data'] ?? [],
        );

        return Response::structured($data ?: ['data' => []]);
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'campaign_id' => $schema->string()->description('The campaign ID to list beats from.')->required(),
            'page' => $schema->integer()->description('Page number (default: 1).'),
            'size' => $schema->integer()->description('Page size (default: 20).'),
        ];
    }
}
