<?php

namespace App\Actions\Archivist\Spotlights;

use App\Actions\Archivist\WriteApiAction;
use App\Data\SpotlightData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class CreateSpotlight extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'session_id' => ['required', 'string'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:4000'],
            'character_name' => ['nullable', 'string', 'max:120'],
            'player_name' => ['nullable', 'string', 'max:120'],
            'style' => ['nullable', 'string', 'max:40'],
            'custom_theme' => ['nullable', 'array'],
            'order_index' => ['nullable', 'integer'],
            'source_moment_id' => ['nullable', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->post('/v1/spotlights', $input->all());
    }

    protected function map(array $data): SpotlightData
    {
        return new SpotlightData($data);
    }
}
