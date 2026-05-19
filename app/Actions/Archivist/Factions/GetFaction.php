<?php

namespace App\Actions\Archivist\Factions;

use App\Actions\Archivist\ApiAction;
use App\Data\FactionData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetFaction extends ApiAction
{
    public static function rules(): array
    {
        return [
            'faction_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/factions/{$input->string('faction_id')}");
    }

    protected function map(array $data): FactionData
    {
        return new FactionData($data);
    }
}
