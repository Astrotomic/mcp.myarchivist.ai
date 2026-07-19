<?php

namespace App\Data;

class LinkMaintenanceResultData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'success' => ['required', 'boolean'],
            'task_id' => ['nullable', 'string'],
        ];
    }
}
