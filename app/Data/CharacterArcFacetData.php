<?php

namespace App\Data;

class CharacterArcFacetData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'title' => ['nullable', 'string'],
            'description' => ['required', 'string'],
            'session_id' => ['nullable', 'string'],
            'session_number' => ['nullable', 'integer'],
            'version_count' => ['required', 'integer'],
            'hidden' => ['required', 'bool'],
            'status' => ['required', 'string', 'in:approved,pending'],
            'run_id' => ['nullable', 'string'],
            'source' => ['nullable', 'string'],
            'replaces_id' => ['nullable', 'string'],
            'previous_title' => ['nullable', 'string'],
            'previous_description' => ['nullable', 'string'],
            'created_at' => ['nullable', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
