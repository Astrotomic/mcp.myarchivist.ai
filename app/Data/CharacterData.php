<?php

namespace App\Data;

class CharacterData extends CharacterDataShort
{
    public static function rules(): array
    {
        return array_merge(parent::rules(), []);
    }
}
