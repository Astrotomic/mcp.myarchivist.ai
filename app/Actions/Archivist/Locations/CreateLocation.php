<?php

namespace App\Actions\Archivist\Locations;

use App\Actions\Archivist\WriteApiAction;
use App\Data\LocationData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class CreateLocation extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'name' => ['required', 'string'],
            'aliases' => ['nullable', 'list'],
            'type' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->post('/v1/locations', $input->all());
    }

    protected function map(array $data): LocationData
    {
        return new LocationData($data);
    }
}
