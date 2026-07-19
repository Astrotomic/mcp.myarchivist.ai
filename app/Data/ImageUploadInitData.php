<?php

namespace App\Data;

class ImageUploadInitData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'object_key' => ['required', 'string'],
            'upload_url' => ['required', 'string'],
            'public_url' => ['required', 'string'],
            'expires_in_seconds' => ['required', 'integer'],
        ];
    }
}
