<?php

namespace App\Actions\Archivist\Characters;

use App\Actions\Archivist\WriteApiAction;
use App\Data\CharacterData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class UpdateCharacter extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'character_id' => ['required', 'string'],
            'character_name' => ['nullable', 'string'],
            'character_alias' => ['nullable', 'string'],
            'character_aliases' => ['nullable', 'list'],
            'player_name' => ['nullable', 'string'],
            'player_handle' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'backstory' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'in:PC,NPC'],
            'image' => ['nullable', 'string'],
            'speaker_id' => ['nullable', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->patch(
            "/v1/characters/{$input->string('character_id')}",
            $input->except('character_id'),
        );
    }

    protected function map(array $data): CharacterData
    {
        return new CharacterData($data);
    }
}
