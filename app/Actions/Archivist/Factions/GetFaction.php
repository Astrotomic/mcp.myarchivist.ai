<?php

namespace App\Actions\Archivist\Factions;

use App\Actions\Archivist\ApiAction;
use App\Actions\Archivist\Concerns\LoadsEntityProperties;
use App\Data\FactionData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetFaction extends ApiAction
{
    use LoadsEntityProperties;

    public static function rules(): array
    {
        return [
            'faction_id' => ['required', 'string'],
            'with_links' => ['nullable', 'boolean'],
            'with_properties' => ['nullable', 'boolean'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/factions/{$input->string('faction_id')}", $input->except(['faction_id', 'with_properties']));
    }

    protected function interpretResponse(ValidatedInput $input, Response $response): FactionData
    {
        return $this->map($this->withProperties($input, $response->fluent()->all(), 'factions'));
    }

    protected function map(array $data): FactionData
    {
        return new FactionData($data);
    }
}
