<?php

namespace Tests\Feature\Data;

use App\Data\CampaignData;
use App\Data\CampaignDataShort;
use App\Data\JournalDataShort;
use App\Exceptions\DtoValidationException;
use App\Exceptions\UnexpectedDtoAttributeException;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\FeatureTestCase;

class ArchivistDtoTest extends FeatureTestCase
{
    public function test_valid_dto_construction_raises_no_exceptions(): void
    {
        $reported = $this->captureReported(function () {
            new CampaignData([
                'id' => 'camp_1',
                'title' => 'Shadows',
                'description' => null,
                'system' => null,
                'public' => false,
                'created_at' => '2024-01-01',
                'updated_at' => '2024-01-01',
                'image' => null,
                'mature' => false,
                'owner_id' => Str::uuid()->toString(),
                'summary' => null,
                'language' => 'en',
                'archived' => false,
                'archived_at' => null,
            ]);
        });

        $this->assertEmpty($reported, 'No exceptions should be reported for a fully valid DTO.');
    }

    public function test_dto_hydrates_attributes_correctly(): void
    {
        $data = new CampaignData([
            'id' => 'camp_1',
            'title' => 'Shadows',
            'description' => null,
            'system' => 'D&D 5e',
            'public' => true,
            'created_at' => '2024-01-01',
            'updated_at' => '2024-01-01',
            'mature' => false,
            'owner_id' => Str::uuid()->toString(),
            'summary' => null,
            'language' => 'en',
        ]);

        $this->assertSame('camp_1', $data->get('id'));
        $this->assertSame('Shadows', $data->get('title'));
        $this->assertTrue($data->get('public'));
    }

    public function test_dto_reports_validation_exception_on_missing_required_field(): void
    {
        $reported = $this->captureReported(function () {
            new CampaignData([
                // 'id' intentionally missing
                'title' => 'Test',
                'public' => false,
                'created_at' => '2024-01-01',
            ]);
        });

        $validationExceptions = array_values(array_filter($reported, fn ($e) => $e instanceof DtoValidationException));

        $this->assertNotEmpty($validationExceptions, 'A DtoValidationException should have been reported.');
        $this->assertStringContainsString('id', $validationExceptions[0]->errors->first());
    }

    public function test_dto_reports_unexpected_attribute_exception(): void
    {
        $reported = $this->captureReported(function () {
            new CampaignData([
                'id' => 'camp_1',
                'title' => 'Test',
                'description' => null,
                'system' => null,
                'public' => false,
                'created_at' => '2024-01-01',
                'updated_at' => '2024-01-01',
                'image' => null,
                'mature' => false,
                'owner_id' => Str::uuid()->toString(),
                'summary' => null,
                'language' => 'en',
                'unknown_new_api_key' => 'surprise!',
            ]);
        });

        $unexpectedExceptions = array_values(array_filter($reported, fn ($e) => $e instanceof UnexpectedDtoAttributeException));

        $this->assertNotEmpty($unexpectedExceptions, 'An UnexpectedDtoAttributeException should have been reported.');
        $this->assertSame(['unknown_new_api_key'], $unexpectedExceptions[0]->keys);
    }

    /**
     * Regression: prove that the unexpected-key check catches new API fields.
     * If the checkForUnexpectedKeys() logic were removed, no exception would be reported.
     */
    public function test_dto_regression_unexpected_key_is_detected(): void
    {
        $reported = $this->captureReported(function () {
            new CampaignData([
                'id' => 'camp_1',
                'title' => 'Test',
                'description' => null,
                'system' => null,
                'public' => false,
                'created_at' => '2024-01-01',
                'a_brand_new_field' => 'added by API team',
            ]);
        });

        $unexpectedExceptions = array_values(array_filter($reported, fn ($e) => $e instanceof UnexpectedDtoAttributeException));
        $this->assertCount(1, $unexpectedExceptions, 'Exactly one UnexpectedDtoAttributeException should be reported for one new field.');
    }

    public function test_campaign_data_short_accepts_archived_fields_from_list_endpoint(): void
    {
        $reported = $this->captureReported(function () {
            new CampaignDataShort([
                'id' => 'camp_1',
                'title' => 'Shadows',
                'description' => null,
                'system' => 'D&D 5e',
                'image' => null,
                'public' => false,
                'mature' => false,
                'owner_id' => Str::uuid()->toString(),
                'archived' => false,
                'archived_at' => null,
                'created_at' => '2024-01-01',
                'updated_at' => '2024-01-01',
            ]);
        });

        $this->assertEmpty($reported);
    }

    public function test_journal_data_short_accepts_content_from_list_endpoint(): void
    {
        $reported = $this->captureReported(function () {
            new JournalDataShort([
                'id' => 'j_1',
                'campaign_id' => 'camp_1',
                'title' => 'Session Recap',
                'content' => 'The party met [[Queen Alice]].',
                'summary' => null,
                'status' => 'draft',
                'folder_id' => null,
                'is_public' => false,
                'is_pinned' => false,
                'tags' => [],
                'author_id' => Str::uuid()->toString(),
                'last_edited_by_id' => Str::uuid()->toString(),
                'token_count' => 12,
                'cover_image' => null,
                'published_at' => null,
                'archived_at' => null,
                'created_at' => '2024-01-01',
                'updated_at' => '2024-01-01',
            ]);
        });

        $this->assertEmpty($reported);
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
