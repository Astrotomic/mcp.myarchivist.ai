<?php

namespace App\Data;

class BeatData extends BeatDataShort
{
    public static function rules(): array
    {
        return array_merge(parent::rules(), [
            'description' => ['nullable', 'string'],
            'metadata' => ['nullable', 'array'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ]);
    }
}
