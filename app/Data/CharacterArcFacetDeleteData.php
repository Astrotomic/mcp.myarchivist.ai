<?php

namespace App\Data;

class CharacterArcFacetDeleteData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'deleted_id' => ['required', 'string'],
            // The earlier version that became current again, if any.
            'restored_id' => ['nullable', 'string'],
        ];
    }
}
