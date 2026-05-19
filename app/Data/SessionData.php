<?php

namespace App\Data;

class SessionData extends SessionDataShort
{
    public static function rules(): array
    {
        return array_merge(parent::rules(), [
            'notes' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'index' => ['required', 'integer'],
            'dg_request_id' => ['nullable', 'string'],
            'pbp_start_msg_url' => ['nullable', 'string'],
            'pbp_end_msg_url' => ['nullable', 'string'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
            'beats' => ['nullable', 'array'],
            'moments' => ['nullable', 'array'],
        ]);
    }
}
