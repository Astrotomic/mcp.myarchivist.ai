<?php

namespace App\Data;

class MomentData extends MomentDataShort
{
    public static function rules(): array
    {
        return array_merge(parent::rules(), [
            'categories' => ['required', 'array'],
        ]);
    }
}
