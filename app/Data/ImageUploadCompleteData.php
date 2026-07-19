<?php

namespace App\Data;

class ImageUploadCompleteData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'url' => ['required', 'string'],
            'attached' => ['required', 'boolean'],
        ];
    }
}
