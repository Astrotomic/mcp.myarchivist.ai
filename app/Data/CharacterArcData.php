<?php

namespace App\Data;

class CharacterArcData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'character_id' => ['required', 'string'],
            // Each theme: {id, key, title, description, facets: [CharacterArcFacetData-shaped objects]}.
            'themes' => ['required', 'list'],
        ];
    }
}
