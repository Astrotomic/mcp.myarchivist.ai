<?php

namespace App\Actions\Archivist\Locations;

use App\Actions\Archivist\WriteApiAction;
use App\Data\LocationData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class UpdateLocation extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'location_id' => ['required', 'string'],
            'name' => ['nullable', 'string'],
            'aliases' => ['nullable', 'list'],
            'type' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->patch(
            "/v1/locations/{$input->string('location_id')}",
            $input->except('location_id'),
        );
    }

    protected function map(array $data): LocationData
    {
        return new LocationData($data);
    }
}
