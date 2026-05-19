<?php

namespace App\Data;

class SessionData extends SessionDataShort
{
    public static function rules(): array
    {
        return array_merge(parent::rules(), [
            'beats' => ['nullable', 'array'],
            'moments' => ['nullable', 'array'],
        ]);
    }
}
