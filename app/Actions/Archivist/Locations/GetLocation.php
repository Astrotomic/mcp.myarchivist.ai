<?php

namespace App\Actions\Archivist\Locations;

use App\Actions\Archivist\ApiAction;
use App\Data\LocationData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetLocation extends ApiAction
{
    public static function rules(): array
    {
        return [
            'location_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/locations/{$input->string('location_id')}");
    }

    protected function map(array $data): LocationData
    {
        return new LocationData($data);
    }
}
