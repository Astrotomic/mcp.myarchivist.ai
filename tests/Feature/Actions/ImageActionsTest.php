<?php

namespace Tests\Feature\Actions;

use App\Actions\Archivist\Images\CompleteImageUpload;
use App\Actions\Archivist\Images\DeleteEntityImage;
use App\Actions\Archivist\Images\GetImageUsage;
use App\Actions\Archivist\Images\InitImageUpload;
use App\Data\ImageDeleteResultData;
use App\Data\ImageUploadCompleteData;
use App\Data\ImageUploadInitData;
use App\Data\ImageUsageData;
use App\Services\ArchivistClient;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

final class ImageActionsTest extends FeatureTestCase
{
    private const BASE_URL = 'https://api.myarchivist.test';

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.archivist.base_url', self::BASE_URL);

        $this->app->instance(
            ArchivistClient::class,
            new ArchivistClient(token: 'test-token'),
        );
    }

    #[Test]
    public function get_image_usage_queries_endpoint_with_campaign_id(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/images/usage*' => Http::response([
                'used' => 3,
                'limit' => 25,
                'can_access' => true,
                'feature_type' => 'image',
                'tier' => 'pro',
                'billing_cycle_start' => '2026-06-01T00:00:00Z',
                'billing_cycle_end' => '2026-07-01T00:00:00Z',
                'message' => null,
            ], 200),
        ]);

        $result = GetImageUsage::make()->execute(['campaign_id' => 'camp_1']);

        $this->assertInstanceOf(ImageUsageData::class, $result);
        $this->assertSame(3, $result->get('used'));
        $this->assertSame(25, $result->get('limit'));
        $this->assertSame('pro', $result->get('tier'));

        Http::assertSent(function (Request $request) {
            return $request->method() === 'GET'
                && str_starts_with($request->url(), self::BASE_URL.'/v1/images/usage')
                && str_contains($request->url(), 'campaign_id=camp_1');
        });
    }

    #[Test]
    public function init_image_upload_posts_to_campaign_scoped_endpoint(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/campaigns/camp_1/images/init' => Http::response([
                'object_key' => 'characters/char_1/1720000000-uuid.png',
                'upload_url' => 'https://s3.example.com/put?sig=abc',
                'public_url' => 'https://cdn.myarchivist.test/characters/char_1/1720000000-uuid.png',
                'expires_in_seconds' => 300,
            ], 200),
        ]);

        $result = InitImageUpload::make()->execute([
            'campaign_id' => 'camp_1',
            'entity_type' => 'character',
            'entity_id' => 'char_1',
            'file_name' => 'portrait.png',
            'content_type' => 'image/png',
        ]);

        $this->assertInstanceOf(ImageUploadInitData::class, $result);
        $this->assertSame('https://s3.example.com/put?sig=abc', $result->get('upload_url'));
        $this->assertSame(300, $result->get('expires_in_seconds'));

        Http::assertSent(function (Request $request) {
            return $request->method() === 'POST'
                && $request->url() === self::BASE_URL.'/v1/campaigns/camp_1/images/init'
                && $request['entity_type'] === 'character'
                && $request['entity_id'] === 'char_1'
                && $request['file_name'] === 'portrait.png'
                && $request['content_type'] === 'image/png'
                && ! array_key_exists('campaign_id', $request->data());
        });
    }

    #[Test]
    public function complete_image_upload_posts_object_key_and_returns_attached_flag(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/campaigns/camp_1/images/complete' => Http::response([
                'url' => 'https://cdn.myarchivist.test/characters/char_1/1720000000-uuid.png',
                'attached' => true,
            ], 200),
        ]);

        $result = CompleteImageUpload::make()->execute([
            'campaign_id' => 'camp_1',
            'object_key' => 'characters/char_1/1720000000-uuid.png',
            'entity_type' => 'character',
            'entity_id' => 'char_1',
            'attach' => true,
        ]);

        $this->assertInstanceOf(ImageUploadCompleteData::class, $result);
        $this->assertTrue((bool) $result->get('attached'));
        $this->assertSame(
            'https://cdn.myarchivist.test/characters/char_1/1720000000-uuid.png',
            $result->get('url'),
        );

        Http::assertSent(function (Request $request) {
            return $request->method() === 'POST'
                && $request->url() === self::BASE_URL.'/v1/campaigns/camp_1/images/complete'
                && $request['object_key'] === 'characters/char_1/1720000000-uuid.png'
                && $request['attach'] === true
                && ! array_key_exists('campaign_id', $request->data());
        });
    }

    #[Test]
    public function delete_entity_image_sends_json_body_with_entity_type_and_id(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/campaigns/camp_1/images' => Http::response([
                'removed' => true,
            ], 200),
        ]);

        $result = DeleteEntityImage::make()->execute([
            'campaign_id' => 'camp_1',
            'entity_type' => 'character',
            'entity_id' => 'char_1',
        ]);

        $this->assertInstanceOf(ImageDeleteResultData::class, $result);
        $this->assertTrue((bool) $result->get('removed'));

        Http::assertSent(function (Request $request) {
            return $request->method() === 'DELETE'
                && $request->url() === self::BASE_URL.'/v1/campaigns/camp_1/images'
                && $request['entity_type'] === 'character'
                && $request['entity_id'] === 'char_1'
                && ! array_key_exists('campaign_id', $request->data());
        });
    }

    #[Test]
    public function delete_entity_image_also_accepts_image_url_variant(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/campaigns/camp_1/images' => Http::response([
                'removed' => true,
            ], 200),
        ]);

        DeleteEntityImage::make()->execute([
            'campaign_id' => 'camp_1',
            'image_url' => 'https://cdn.myarchivist.test/characters/char_1/abc.png',
        ]);

        Http::assertSent(function (Request $request) {
            return $request->method() === 'DELETE'
                && $request['image_url'] === 'https://cdn.myarchivist.test/characters/char_1/abc.png'
                && ! array_key_exists('entity_type', $request->data())
                && ! array_key_exists('entity_id', $request->data());
        });
    }
}
