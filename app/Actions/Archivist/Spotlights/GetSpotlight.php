<?php

namespace App\Actions\Archivist\Spotlights;

use App\Actions\Archivist\ApiAction;
use App\Data\SpotlightData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetSpotlight extends ApiAction
{
    public static function rules(): array
    {
        return [
            'spotlight_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/spotlights/{$input->string('spotlight_id')}");
    }

    protected function map(array $data): SpotlightData
    {
        return new SpotlightData($data);
    }
}
