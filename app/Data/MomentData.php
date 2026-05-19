<?php

namespace App\Data;

class MomentData extends MomentDataShort
{
    public static function rules(): array
    {
        return array_merge(parent::rules(), [
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'categories' => ['required', 'array'],
            'pending' => ['required', 'boolean'],
            'discovered' => ['required', 'boolean'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ]);
    }
}
