<?php

namespace App\Data;

class ImageDeleteResultData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'removed' => ['required', 'boolean'],
        ];
    }
}
