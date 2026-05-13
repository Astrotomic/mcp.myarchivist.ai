<?php

namespace App\Exceptions;

class UnexpectedDtoAttributeException extends ArchivistDtoMismatchException
{
    public function __construct(
        public readonly string $dtoClass,
        public readonly string $key,
        public readonly mixed $value,
    ) {
        parent::__construct(
            "Unexpected attribute '{$key}' on DTO {$dtoClass}",
        );
    }
}
