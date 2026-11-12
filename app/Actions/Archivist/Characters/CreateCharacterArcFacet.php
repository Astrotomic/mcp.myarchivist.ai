<?php

namespace App\Actions\Archivist\Characters;

use App\Actions\Archivist\WriteApiAction;
use App\Data\CharacterArcFacetData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class CreateCharacterArcFacet extends WriteApiAction
{
    public const THEMES = 'CharacterDevelopment,Relationships,CombatCapability,KnowledgeDiscovery,ReputationAffiliation,ItemsResources,ConditionsConsequences';

    public static function rules(): array
    {
        return [
            'character_id' => ['required', 'string'],
            'theme' => ['required', 'string', 'in:'.self::THEMES],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:10000'],
            'session_id' => ['nullable', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->post(
            "/v1/characters/{$input->string('character_id')}/arc/facets",
            $input->except('character_id'),
        );
    }

    protected function map(array $data): CharacterArcFacetData
    {
        return new CharacterArcFacetData($data);
    }
}
