<?php

namespace Tests\Feature;

use App\Exceptions\DtoValidationException;
use App\Mcp\Data\CampaignData;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Tests\TestCase;

class DtoContractTest extends TestCase
{
    private function validCampaignAttributes(): array
    {
        return [
            'id' => 'camp_1',
            'title' => 'Shadows',
            'description' => null,
            'system' => null,
            'image' => null,
            'public' => false,
            'mature' => false,
            'owner_id' => 'user_123',
            'can_manage' => true,
            'created_at' => '2024-01-01',
        ];
    }

    public function test_valid_dto_construction_raises_no_exceptions(): void
    {
        $reported = $this->captureReported(function () {
            new CampaignData($this->validCampaignAttributes());
        });

        $this->assertEmpty($reported, 'No exceptions should be reported for a fully valid DTO.');
    }

    public function test_dto_hydrates_attributes_correctly(): void
    {
        $data = new CampaignData($this->validCampaignAttributes());

        $this->assertSame('camp_1', $data->get('id'));
        $this->assertSame('Shadows', $data->get('title'));
        $this->assertFalse($data->get('public'));
    }

    public function test_dto_reports_validation_exception_on_missing_required_field(): void
    {
        $reported = $this->captureReported(function () {
            $attrs = $this->validCampaignAttributes();
            unset($attrs['id']);
            new CampaignData($attrs);
        });

        $validationExceptions = array_values(array_filter($reported, fn ($e) => $e instanceof DtoValidationException));

        $this->assertNotEmpty($validationExceptions, 'A DtoValidationException should have been reported.');
        $this->assertStringContainsString('id', $validationExceptions[0]->errors->first());
    }

    public function test_dto_strips_unexpected_attributes_silently(): void
    {
        Log::shouldReceive('debug')->once()->withArgs(function ($message, $context) {
            return str_contains($message, 'ignoring unexpected attributes')
                && in_array('unknown_new_api_key', $context['keys']);
        });

        $attrs = $this->validCampaignAttributes();
        $attrs['unknown_new_api_key'] = 'surprise!';

        $data = new CampaignData($attrs);

        $this->assertNull($data->get('unknown_new_api_key'), 'Unknown keys should be stripped from the DTO.');
    }

    public function test_dto_preserves_known_attributes_when_stripping_unknown(): void
    {
        Log::shouldReceive('debug')->once();

        $attrs = $this->validCampaignAttributes();
        $attrs['a_brand_new_field'] = 'added by API team';

        $data = new CampaignData($attrs);

        $this->assertSame('camp_1', $data->get('id'));
        $this->assertSame('Shadows', $data->get('title'));
        $this->assertArrayNotHasKey('a_brand_new_field', $data->toArray());
    }

    /**
     * Collect all exceptions that are `report()`-ed during execution of $callback.
     *
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

                public function render($request, \Throwable $e): SymfonyResponse
                {
                    throw $e;
                }
            };
        });

        $callback();

        return $reported;
    }
}
