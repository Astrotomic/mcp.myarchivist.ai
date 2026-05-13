<?php

namespace App\Exceptions;

use RuntimeException;
use Throwable;

class ArchivistApiException extends RuntimeException
{
    public function __construct(
        public readonly int $status,
        public readonly string $detail,
        string $message = '',
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            $message !== '' ? $message : "MyArchivist API error {$status}: {$detail}",
            $status,
            $previous,
        );
    }
}
