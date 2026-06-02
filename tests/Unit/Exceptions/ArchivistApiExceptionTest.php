<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\ArchivistApiException;
use Illuminate\Http\Client\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\UnitTestCase;

final class ArchivistApiExceptionTest extends UnitTestCase
{
    #[Test]
    public function it_extracts_string_detail(): void
    {
        $response = new Response(new \GuzzleHttp\Psr7\Response(
            401,
            ['Content-Type' => 'application/json'],
            '{"detail":"Invalid access token"}'
        ));

        $this->assertSame('Invalid access token', ArchivistApiException::extractDetail($response));
    }

    #[Test]
    public function it_extracts_fastapi_validation_detail(): void
    {
        $response = new Response(new \GuzzleHttp\Psr7\Response(
            422,
            ['Content-Type' => 'application/json'],
            json_encode([
                'detail' => [[
                    'type' => 'greater_than_equal',
                    'loc' => ['query', 'page'],
                    'msg' => 'Input should be greater than or equal to 1',
                    'input' => 0,
                ]],
            ], JSON_THROW_ON_ERROR)
        ));

        $this->assertSame(
            'page: Input should be greater than or equal to 1',
            ArchivistApiException::extractDetail($response)
        );
    }

    #[Test]
    public function it_prefers_message_when_detail_is_an_array(): void
    {
        $response = new Response(new \GuzzleHttp\Psr7\Response(
            422,
            ['Content-Type' => 'application/json'],
            json_encode([
                'detail' => [[
                    'loc' => ['query', 'page'],
                    'msg' => 'Input should be greater than or equal to 1',
                ]],
                'message' => 'page: Input should be greater than or equal to 1',
            ], JSON_THROW_ON_ERROR)
        ));

        $this->assertSame(
            'page: Input should be greater than or equal to 1',
            ArchivistApiException::extractDetail($response)
        );
    }

    #[Test]
    public function it_builds_exception_from_response(): void
    {
        $response = new Response(new \GuzzleHttp\Psr7\Response(
            403,
            ['Content-Type' => 'application/json'],
            '{"detail":"Subscription inactive"}'
        ));

        $exception = ArchivistApiException::fromResponse($response);

        $this->assertSame(403, $exception->status);
        $this->assertSame('Subscription inactive', $exception->detail);
        $this->assertSame('MyArchivist API error 403: Subscription inactive', $exception->getMessage());
    }
}
