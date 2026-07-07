<?php

namespace Tests\Feature\Data;

use App\Data\SuccessData;
use App\Exceptions\DtoValidationException;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\FeatureTestCase;

class SuccessDataTest extends FeatureTestCase
{
    #[Test]
    public function it_accepts_success_true_with_id(): void
    {
        $reported = $this->captureReported(function () {
            new SuccessData([
                'success' => true,
                'id' => 'record_1',
            ]);
        });

        $this->assertEmpty($reported);
    }

    #[Test]
    public function it_accepts_success_true_without_id(): void
    {
        $reported = $this->captureReported(function () {
            new SuccessData(['success' => true]);
        });

        $this->assertEmpty($reported);
    }

    #[Test]
    public function it_reports_validation_errors_when_success_missing(): void
    {
        $reported = $this->captureReported(function () {
            new SuccessData(['id' => 'record_1']);
        });

        $validationExceptions = array_values(array_filter(
            $reported,
            fn ($e) => $e instanceof DtoValidationException,
        ));
        $this->assertCount(1, $validationExceptions);
    }

    #[Test]
    public function it_produces_json_schema_with_success_and_id_keys(): void
    {
        $schema = SuccessData::toJsonSchema();

        $this->assertArrayHasKey('success', $schema);
        $this->assertArrayHasKey('id', $schema);
    }

    /**
     * @return list<\Throwable>
     */
    private function captureReported(callable $callback): array
    {
        $reported = [];

        $this->app->bind(ExceptionHandler::class, function ($app) use (&$reported) {
            return new class($app, $reported) extends Handler
            {
                /** @param list<\Throwable> $captured */
                public function __construct($app, private array &$captured)
                {
                    parent::__construct($app);
                }

                public function report(\Throwable $e): void
                {
                    $this->captured[] = $e;
                }

                public function render($request, \Throwable $e): Response
                {
                    throw $e;
                }
            };
        });

        $callback();

        return $reported;
    }
}
