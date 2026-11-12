<?php

namespace App\Actions\Archivist\Locations;

use App\Actions\Archivist\ApiAction;
use App\Actions\Archivist\Concerns\LoadsEntityProperties;
use App\Data\LocationData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetLocation extends ApiAction
{
    use LoadsEntityProperties;

    public static function rules(): array
    {
        return [
            'location_id' => ['required', 'string'],
            'with_links' => ['nullable', 'boolean'],
            'with_properties' => ['nullable', 'boolean'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/locations/{$input->string('location_id')}", $input->except(['location_id', 'with_properties']));
    }

    protected function interpretResponse(ValidatedInput $input, Response $response): LocationData
    {
        return $this->map($this->withProperties($input, $response->fluent()->all(), 'locations'));
    }

    protected function map(array $data): LocationData
    {
        return new LocationData($data);
    }
}
