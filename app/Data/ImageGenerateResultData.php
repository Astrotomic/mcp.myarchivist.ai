<?php

namespace App\Data;

class ImageGenerateResultData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'url' => ['required', 'string'],
        ];
    }
}
