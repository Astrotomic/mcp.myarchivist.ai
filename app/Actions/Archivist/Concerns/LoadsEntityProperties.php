<?php

namespace App\Actions\Archivist\Concerns;

use App\Services\ArchivistClient;
use Illuminate\Support\ValidatedInput;

/**
 * Adds the entity's Properties tables to a Get* response when the caller
 * passes `with_properties: true` (one extra API call).
 *
 * @property-read ArchivistClient $client
 */
trait LoadsEntityProperties
{
    /**
     * @param  array<string, mixed>  $data
     * @param  'characters'|'factions'|'locations'|'items'  $type
     * @return array<string, mixed>
     */
    protected function withProperties(ValidatedInput $input, array $data, string $type): array
    {
        if (! $input->boolean('with_properties') || ! is_string($data['id'] ?? null) || ! is_string($data['campaign_id'] ?? null)) {
            return $data;
        }

        $properties = $this->client->get("/v1/entities/{$data['id']}/properties", [
            'campaign_id' => $data['campaign_id'],
            'type' => $type,
        ]);
        $data['properties'] = $properties->json('tables') ?? [];

        return $data;
    }
}
