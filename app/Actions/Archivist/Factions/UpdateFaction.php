<?php

namespace App\Actions\Archivist\Factions;

use App\Actions\Archivist\WriteApiAction;
use App\Data\FactionData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class UpdateFaction extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'faction_id' => ['required', 'string'],
            'name' => ['nullable', 'string'],
            'aliases' => ['nullable', 'list'],
            'type' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->patch(
            "/v1/factions/{$input->string('faction_id')}",
            $input->except('faction_id'),
        );
    }

    protected function map(array $data): FactionData
    {
        return new FactionData($data);
    }
}
