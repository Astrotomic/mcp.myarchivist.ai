<?php

namespace App\Mcp\Tools\Concerns;

use App\Data\ArchivistDto;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Support\Str;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;

trait HasArchivistOutputSchema
{
    /**
     * Get the tool's output schema.
     *
     * @return array<string, Type>
     */
    public function outputSchema(JsonSchema $schema): array
    {
        /** @var class-string<ArchivistDto> $dtoClass */
        $dtoClass = $this->outputDtoClass();

        if ($this->hasListOutput()) {
            return [
                'data' => $schema->array()
                    ->description("List of {$this->outputEntityDescription($dtoClass)} records.")
                    ->required(),
                'total' => $schema->integer()->description('Total number of matching records.'),
                'page' => $schema->integer()->description('Current page number.'),
                'size' => $schema->integer()->description('Page size.'),
                'pages' => $schema->integer()->description('Total number of pages.'),
            ];
        }

        return $dtoClass::jsonSchema($schema);
    }

    /**
     * @return class-string<ArchivistDto>
     */
    abstract protected function outputDtoClass(): string;

    protected function newOutputDto(array $data): ArchivistDto
    {
        return new ($this->outputDtoClass())($data);
    }

    protected function structuredResponse(array $data): ResponseFactory
    {
        if ($this->hasListOutput()) {
            $data['data'] = collect($data['data'] ?? [])
                ->map(fn (array $item) => $this->newOutputDto($item))
                ->toArray();
        } else {
            $data = $this->newOutputDto($data)->toArray();
        }

        return Response::structured($data);
    }

    private function hasListOutput(): bool
    {
        return str_starts_with(class_basename(static::class), 'List');
    }

    /**
     * @param  class-string<ArchivistDto>  $dtoClass
     */
    private function outputEntityDescription(string $dtoClass): string
    {
        return (string) Str::of($dtoClass)
            ->classBasename()
            ->beforeLast('Data')
            ->headline()
            ->lower();
    }
}
