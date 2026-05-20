<?php

namespace App\Mcp\Tools;

use App\Actions\Archivist\ApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\ArchivistDto;
use App\Exceptions\ArchivistApiException;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Illuminate\Validation\ValidationException;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;

abstract class Tool extends \Laravel\Mcp\Server\Tool
{
    abstract protected function action(): ApiAction;

    public function handle(Request $request): Response|ResponseFactory
    {
        try {
            $response = $this->action()->execute($request->all());
        } catch (ArchivistApiException $e) {
            return Response::error($e->getMessage());
        } catch (ValidationException $e) {
            return Response::error(json_encode($e->errors()));
        }

        if ($response instanceof LengthAwarePaginator) {
            $structured = Arr::except($response->toArray(), [
                'first_page_url',
                'last_page_url',
                'next_page_url',
                'prev_page_url',
                'links',
                'path',
            ]);
        } else {
            $structured = $response->toArray();
        }

        return Response::structured($structured);
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return $this->action()->toJsonSchema();
    }

    /**
     * @return array<string, Type>
     *
     * @throws ReflectionException
     */
    public function outputSchema(JsonSchema $schema): array
    {
        $method = new ReflectionMethod($this->action(), 'map');
        $return = $method->getReturnType();

        if ($return instanceof ReflectionNamedType) {
            if ($return->getName() === ArchivistDtoCollection::class) {
                return [
                    'data' => $schema->array()->description("List of {$this->entityLabel()} records.")->required(),
                    'total' => $schema->integer()->description('Total number of matching records.'),
                    'page' => $schema->integer()->description('Current page number.'),
                    'size' => $schema->integer()->description('Page size.'),
                    'pages' => $schema->integer()->description('Total number of pages.'),
                ];
            }

            if (is_subclass_of($return->getName(), ArchivistDto::class)) {
                $class = new ReflectionClass($return->getName());
                $instance = $class->newInstanceWithoutConstructor();

                return $instance->toJsonSchema();
            }
        }

        return [];
    }

    private function entityLabel(): string
    {
        return (string) Str::of(static::class)
            ->classBasename()
            ->beforeLast('Tool')
            ->whenStartsWith('Get', fn (Stringable $str) => $str->after('Get'))
            ->whenStartsWith('List', fn (Stringable $str) => $str->after('List'))
            ->headline()
            ->lower();
    }
}
