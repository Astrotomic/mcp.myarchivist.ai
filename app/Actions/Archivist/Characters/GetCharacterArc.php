<?php

namespace App\Actions\Archivist\Characters;

use App\Actions\Archivist\ApiAction;
use App\Data\CharacterArcData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetCharacterArc extends ApiAction
{
    public static function rules(): array
    {
        return [
            'character_id' => ['required', 'string'],
            'status' => ['nullable', 'string', 'in:approved,pending,all'],
            'session_id' => ['nullable', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get(
            "/v1/characters/{$input->string('character_id')}/arc",
            $input->except('character_id'),
        );
    }

    protected function map(array $data): CharacterArcData
    {
        return new CharacterArcData($data);
    }
}
