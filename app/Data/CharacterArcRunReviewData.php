<?php

namespace App\Data;

class CharacterArcRunReviewData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'run_id' => ['required', 'string'],
            'action' => ['required', 'string', 'in:approve,decline'],
            'affected' => ['required', 'integer'],
            'restored_ids' => ['nullable', 'list'],
        ];
    }
}
