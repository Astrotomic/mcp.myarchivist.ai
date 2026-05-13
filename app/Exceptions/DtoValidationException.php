<?php

namespace App\Exceptions;

use Illuminate\Support\MessageBag;
use Throwable;

class DtoValidationException extends ArchivistDtoMismatchException
{
    public function __construct(
        public readonly string $dtoClass,
        public readonly MessageBag $errors,
        ?Throwable $previous = null,
    ) {
        $summary = implode('; ', $errors->all());

        parent::__construct(
            "DTO validation failed for {$dtoClass}: {$summary}",
            0,
            $previous,
        );
    }
}
