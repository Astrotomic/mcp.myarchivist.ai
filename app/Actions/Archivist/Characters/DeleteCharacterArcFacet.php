<?php

namespace App\Actions\Archivist\Characters;

use App\Actions\Archivist\WriteApiAction;
use App\Data\CharacterArcFacetDeleteData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class DeleteCharacterArcFacet extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'character_id' => ['required', 'string'],
            'facet_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->delete(
            "/v1/characters/{$input->string('character_id')}/arc/facets/{$input->string('facet_id')}",
        );
    }

    protected function map(array $data): CharacterArcFacetDeleteData
    {
        return new CharacterArcFacetDeleteData($data);
    }
}
