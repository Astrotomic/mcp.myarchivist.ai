<?php

namespace App\Data;

class ItemData extends ItemDataShort
{
    public static function rules(): array
    {
        return array_merge(parent::rules(), [
        ]);
    }
}
