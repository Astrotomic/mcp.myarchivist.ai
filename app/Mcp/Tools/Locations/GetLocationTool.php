<?php

namespace App\Mcp\Tools\Locations;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\LocationData;
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

#[Description('Get a specific location by ID.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetLocationTool extends Tool
{
    use HasArchivistOutputSchema;

    #[\Override]
    protected function outputDtoClass(): string
    {
        return LocationData::class;
    }

    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'location_id' => ['required', 'string'],
        ], [
            'location_id.required' => 'You must provide a location_id to retrieve a specific location.',
        ]);

        try {
            $data = $this->client->get("/v1/locations/{$validated['location_id']}");
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to get location '{$validated['location_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        return $this->structuredResponse($data);
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'location_id' => $schema->string()->description('The ID of the location to retrieve.')->required(),
        ];
    }
}
