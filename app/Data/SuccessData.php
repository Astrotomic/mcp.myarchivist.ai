<?php

namespace App\Data;

class SuccessData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'success' => ['required', 'boolean'],
            'id' => ['nullable', 'string'],
        ];
    }
}
