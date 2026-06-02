<?php

namespace App\Exceptions;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Str;
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

    public static function fromResponse(Response $response, ?Throwable $previous = null): self
    {
        return new self(
            status: $response->status(),
            detail: self::extractDetail($response),
            previous: $previous,
        );
    }

    public static function extractDetail(Response $response): string
    {
        $body = $response->json();

        if (! is_array($body)) {
            return Str::limit($response->body(), 500);
        }

        if (! array_key_exists('detail', $body)) {
            foreach (['message', 'error'] as $key) {
                if (isset($body[$key]) && is_string($body[$key])) {
                    return $body[$key];
                }
            }

            return Str::limit($response->body(), 500);
        }

        $detail = $body['detail'];

        if (isset($body['message']) && is_string($body['message']) && is_array($detail)) {
            return $body['message'];
        }

        if (is_string($detail)) {
            return $detail;
        }

        if (is_array($detail)) {
            if (isset($detail[0]) && is_array($detail[0])) {
                return collect($detail)
                    ->map(function (mixed $error): string {
                        if (! is_array($error)) {
                            return 'Validation error';
                        }

                        $locations = $error['loc'] ?? [];
                        if (! is_array($locations)) {
                            $locations = [];
                        }

                        $location = collect($locations)
                            ->reject(fn (mixed $part): bool => $part === 'body' || $part === 'query')
                            ->map(function (mixed $part): string {
                                if (is_string($part)) {
                                    return $part;
                                }

                                if (is_int($part)) {
                                    return (string) $part;
                                }

                                return '';
                            })
                            ->implode('.');

                        $msg = $error['msg'] ?? null;
                        $message = match (true) {
                            is_string($msg) => $msg,
                            is_int($msg), is_float($msg), is_bool($msg) => (string) $msg,
                            default => 'Validation error',
                        };

                        return $location !== '' ? "{$location}: {$message}" : $message;
                    })
                    ->implode('; ');
            }

            return json_encode($detail, JSON_THROW_ON_ERROR);
        }

        return Str::limit(json_encode($detail, JSON_THROW_ON_ERROR), 500);
    }
}
