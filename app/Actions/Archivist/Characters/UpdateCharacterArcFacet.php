<?php

namespace App\Actions\Archivist\Characters;

use App\Actions\Archivist\WriteApiAction;
use App\Data\CharacterArcFacetData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class UpdateCharacterArcFacet extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'character_id' => ['required', 'string'],
            'facet_id' => ['required', 'string'],
            'theme' => ['nullable', 'string', 'in:'.CreateCharacterArcFacet::THEMES],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'session_id' => ['nullable', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->patch(
            "/v1/characters/{$input->string('character_id')}/arc/facets/{$input->string('facet_id')}",
            $input->except(['character_id', 'facet_id']),
        );
    }

    protected function map(array $data): CharacterArcFacetData
    {
        return new CharacterArcFacetData($data);
    }
}
