<?php

namespace App\Data;

class JournalData extends JournalDataShort
{
    public static function rules(): array
    {
        return array_merge(parent::rules(), [
            'content' => ['nullable', 'string'],
            'content_rich' => ['nullable', 'array'],
            'content_metadata' => ['nullable', 'array'],
            'permission_level' => ['nullable', 'string'],
        ]);
    }
}
