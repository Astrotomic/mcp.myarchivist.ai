<?php

namespace Tests\Feature\Mcp\Tools;

use App\Mcp\Tools\Tool;
use Illuminate\JsonSchema\JsonSchemaTypeFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use Spatie\Snapshots\MatchesSnapshots;
use Symfony\Component\Finder\Finder;
use Tests\FeatureTestCase;

final class ToolSchemaTest extends FeatureTestCase
{
    use MatchesSnapshots;

    /**
     * @return array<string, array{0: class-string<Tool>}>
     */
    public static function toolProvider(): array
    {
        $finder = new Finder;
        $finder->files()->in(dirname(__DIR__, 4).'/app/Mcp/Tools')->name('*Tool.php');

        $tools = [];
        foreach ($finder as $file) {
            $relativePath = $file->getRelativePathname();
            $className = 'App\\Mcp\\Tools\\'.str_replace(['/', '.php'], ['\\', ''], $relativePath);

            if (! class_exists($className)) {
                continue;
            }

            $reflection = new ReflectionClass($className);
            if ($reflection->isAbstract()) {
                continue;
            }

            /** @var class-string<Tool> $className */
            $tools[$className] = [$className];
        }

        return $tools;
    }

    #[Test]
    #[DataProvider('toolProvider')]
    public function it_has_valid_input_schema(string $toolClass): void
    {
        /** @var Tool $tool */
        $tool = $this->app->make($toolClass);
        $schema = $this->app->make(JsonSchemaTypeFactory::class);

        $inputSchema = $tool->schema($schema);

        $this->assertMatchesJsonSnapshot(
            $schema->object($inputSchema)->toArray()
        );
    }

    #[Test]
    #[DataProvider('toolProvider')]
    public function it_has_valid_output_schema(string $toolClass): void
    {
        /** @var Tool $tool */
        $tool = $this->app->make($toolClass);
        $schema = $this->app->make(JsonSchemaTypeFactory::class);

        $outputSchema = $tool->outputSchema($schema);

        $this->assertMatchesJsonSnapshot(
            $schema->object($outputSchema)->toArray()
        );
    }
}
