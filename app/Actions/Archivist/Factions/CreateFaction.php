<?php

namespace App\Actions\Archivist\Factions;

use App\Actions\Archivist\WriteApiAction;
use App\Data\FactionData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class CreateFaction extends WriteApiAction
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
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->post('/v1/factions', $input->all());
    }

    protected function map(array $data): FactionData
    {
        return new FactionData($data);
    }
}
