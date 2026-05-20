<?php

namespace App\Mcp\Content;

use Laravel\Mcp\Server\Concerns\HasMeta;
use Laravel\Mcp\Server\Contracts\Content;
use Laravel\Mcp\Server\Prompt;
use Laravel\Mcp\Server\Resource;
use Laravel\Mcp\Server\Tool;

class EmbeddedResource implements Content
{
    use HasMeta;

    public function __construct(
        protected string $uri,
        protected string $text,
        protected string $mimeType = 'text/plain',
    ) {}

    public function toTool(Tool $tool): array
    {
        return $this->toArray();
    }

    public function toPrompt(Prompt $prompt): array
    {
        return $this->toArray();
    }

    public function toResource(Resource $resource): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return $this->text;
    }

    public function toArray(): array
    {
        return $this->mergeMeta([
            'type' => 'resource',
            'resource' => [
                'uri' => $this->uri,
                'text' => $this->text,
                'mimeType' => $this->mimeType,
            ],
        ]);
    }
}
