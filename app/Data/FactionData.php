<?php

namespace App\Data;

class FactionData extends FactionDataShort
{
    public static function rules(): array
    {
        return array_merge(parent::rules(), [
        ]);
    }
}
