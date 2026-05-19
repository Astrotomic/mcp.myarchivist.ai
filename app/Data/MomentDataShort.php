<?php

namespace App\Data;

class MomentDataShort extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'session_id' => ['nullable', 'string'],
            'label' => ['required', 'string'],
            'index' => ['required', 'integer'],
            'approved' => ['required', 'boolean'],
        ];
    }
}
