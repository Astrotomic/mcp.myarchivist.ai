<?php

namespace App\Contracts;

use Illuminate\JsonSchema\Types\Type;

interface JsonSchemaable
{
    /**
     * @return array<string, Type>
     */
    public static function toJsonSchema(): array;
}
