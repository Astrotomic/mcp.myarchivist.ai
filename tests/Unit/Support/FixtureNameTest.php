<?php

namespace Tests\Unit\Support;

use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Client\Request as HttpRequest;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\FixtureName;
use Tests\UnitTestCase;

final class FixtureNameTest extends UnitTestCase
{
    #[Test]
    public function it_builds_a_stable_name_from_sorted_request_data(): void
    {
        $first = new HttpRequest(new Request(
            'POST',
            'https://api.myarchivist.ai/v1/ask?size=100&page=2',
            ['Content-Type' => 'application/json'],
            '{"messages":[{"content":"Question","role":"user"}],"campaign_id":"campaign"}',
        ));
        $second = new HttpRequest(new Request(
            'POST',
            'https://api.myarchivist.ai/v1/ask?page=2&size=100',
            ['Content-Type' => 'application/json'],
            '{"campaign_id":"campaign","messages":[{"role":"user","content":"Question"}]}',
        ));

        $this->assertSame(
            FixtureName::forHttpRequest($first),
            FixtureName::forHttpRequest($second),
        );
        $this->assertStringStartsWith(
            'api-myarchivist-ai/v1/ask/POST/',
            FixtureName::forHttpRequest($first),
        );
    }
}
