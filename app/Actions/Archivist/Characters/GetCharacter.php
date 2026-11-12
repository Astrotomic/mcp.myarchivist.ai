<?php

namespace App\Actions\Archivist\Characters;

use App\Actions\Archivist\ApiAction;
use App\Actions\Archivist\Concerns\LoadsEntityProperties;
use App\Data\CharacterData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetCharacter extends ApiAction
{
    use LoadsEntityProperties;

    public static function rules(): array
    {
        return [
            'character_id' => ['required', 'string'],
            'with_links' => ['nullable', 'boolean'],
            'with_properties' => ['nullable', 'boolean'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/characters/{$input->string('character_id')}", $input->except(['character_id', 'with_properties']));
    }

    protected function interpretResponse(ValidatedInput $input, Response $response): CharacterData
    {
        return $this->map($this->withProperties($input, $response->fluent()->all(), 'characters'));
    }

    protected function map(array $data): CharacterData
    {
        return new CharacterData($data);
    }
}
