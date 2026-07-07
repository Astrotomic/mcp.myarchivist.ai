<?php

namespace App\Data;

class ImageUsageData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'used' => ['required', 'integer'],
            'limit' => ['required', 'integer'],
            'can_access' => ['required', 'boolean'],
            'feature_type' => ['nullable', 'string'],
            'tier' => ['required', 'string'],
            'billing_cycle_start' => ['nullable', 'string'],
            'billing_cycle_end' => ['nullable', 'string'],
            'message' => ['nullable', 'string'],
        ];
    }
}
