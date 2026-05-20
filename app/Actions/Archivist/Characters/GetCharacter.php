<?php

namespace App\Actions\Archivist\Characters;

use App\Actions\Archivist\ApiAction;
use App\Data\CharacterData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetCharacter extends ApiAction
{
    public static function rules(): array
    {
        return [
            'character_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/characters/{$input->string('character_id')}");
    }

    protected function map(array $data): CharacterData
    {
        return new CharacterData($data);
    }
}
