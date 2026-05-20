<?php

namespace App\Exceptions;

class UnexpectedDtoAttributeException extends ArchivistDtoMismatchException
{
    public function __construct(
        public readonly string $dtoClass,
        public readonly array $keys,
    ) {
        $attributes = implode(', ', $keys);

        parent::__construct(
            "Unexpected attributes '{$attributes}' on DTO {$dtoClass}",
        );
    }
}
