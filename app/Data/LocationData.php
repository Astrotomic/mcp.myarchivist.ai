<?php

namespace App\Data;

class LocationData extends LocationDataShort
{
    public static function rules(): array
    {
        return array_merge(parent::rules(), [
        ]);
    }
}
