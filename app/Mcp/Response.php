<?php

namespace App\Mcp;

use App\Mcp\Content\EmbeddedResource;
use App\Mcp\Resources\Resource;
use Illuminate\Support\Str;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response as BaseResponse;
use Laravel\Mcp\Server\Contracts\HasUriTemplate;

final class Response extends BaseResponse
{
    public static function embeddedResource(Resource $resource, array $arguments = []): self
    {
        $uri = $resource->uri();
        if ($resource instanceof HasUriTemplate) {
            $uri = strtr(
                $uri,
                collect($arguments)->keyBy(fn (mixed $_, string $key): string => Str::of($key)->start('{')->finish('}'))->all(),
            );
        }

        $text = $resource->handle(new Request($arguments))->content();

        return new self(new EmbeddedResource(
            uri: $uri,
            text: $text,
            mimeType: $resource->mimeType(),
        ));
    }
}
