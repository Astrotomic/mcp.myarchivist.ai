<?php

namespace App\Actions\Archivist\Properties;

use App\Actions\Archivist\WriteApiAction;
use App\Data\EntityPropertiesData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class UpdateEntityProperties extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'entity_type' => ['required', 'string', 'in:characters,factions,locations,items'],
            'entity_id' => ['required', 'string'],
            'properties' => ['required', 'array'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        $query = http_build_query([
            'campaign_id' => $input->string('campaign_id')->toString(),
            'type' => $input->string('entity_type')->toString(),
        ]);

        return $this->client->patch(
            "/v1/entities/{$input->string('entity_id')}/properties?{$query}",
            ['properties' => $input->array('properties')],
        );
    }

    protected function map(array $data): EntityPropertiesData
    {
        return new EntityPropertiesData($data);
    }
}
