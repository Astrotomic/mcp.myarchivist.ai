<?php

namespace Tests;

use App\Mcp\Data\CampaignData;
use App\Mcp\Data\CampaignDataShort;
use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        AssertableJson::macro('assertPaginatedList', function (Closure $assertDataItem): AssertableJson {
            /** @var AssertableJson $this */
            return $this
                ->whereType('total', 'integer')
                ->whereType('page', 'integer')
                ->whereType('size', 'integer')
                ->whereType('pages', 'integer')
                ->whereType('data', 'array')
                ->has('data', fn (AssertableJson $data) => $data->each($assertDataItem));
        });

        AssertableJson::macro('assertStructuredData', function (string $resource): AssertableJson {
            /** @var AssertableJson $this */
            return match ($resource) {
                CampaignDataShort::class => $this
                    ->whereType('id', 'string')
                    ->whereType('title', 'string')
                    ->whereType('description', 'string')
                    ->whereType('system', 'string')
                    ->whereType('image', 'string')
                    ->where('image', fn (string $image) => Str::isUrl($image))
                    ->whereType('public', 'boolean')
                    ->whereType('mature', 'boolean')
                    ->whereType('owner_id', 'string')
                    ->where('owner_id', fn (string $ownerId) => Str::isUuid($ownerId))
                    ->whereType('can_manage', 'boolean')
                    ->whereType('created_at', 'string')
                    ->where('created_at', fn (string $createdAt) => Carbon::make($createdAt)?->isValid() ?? false),
                CampaignData::class => $this
                    ->whereType('id', 'string')
                    ->whereType('title', 'string')
                    ->whereType('description', 'string')
                    ->whereType('summary', 'string')
                    ->whereType('system', 'string')
                    ->whereType('language', 'string')
                    ->where('language', fn(string $language) => ctype_lower($language))
                    ->where('language', fn(string $language) => strlen($language) === 2)
                    ->whereType('chat_tone', ['string', 'null'])
                    ->whereType('image', 'string')
                    ->where('image', fn (string $image) => Str::isUrl($image))
                    ->whereType('public', 'boolean')
                    ->whereType('mature', 'boolean')
                    ->whereType('ai_image_gen', 'boolean')
                    ->whereType('new', 'boolean')
                    ->whereType('archived', 'boolean')
                    ->whereType('bot_active', 'boolean')
                    ->whereType('flagged', 'boolean')
                    ->whereType('indexed', 'boolean')
                    ->whereType('players', 'array')
                    ->whereType('keywords', 'array')
                    ->whereType('kill_list', 'array')
                    ->whereType('owner_id', 'string')
                    ->where('owner_id', fn (string $ownerId) => Str::isUuid($ownerId))
                    ->whereType('can_manage', 'boolean')
                    ->whereType('created_at', 'string')
                    ->where('created_at', fn (string $createdAt) => Carbon::make($createdAt)?->isValid() ?? false)
                    ->whereType('updated_at', ['string', 'null'])
                    ->where('updated_at', fn (?string $updatedAt) => Carbon::make($updatedAt)?->isValid() ?? true)
                    ->whereType('archived_at', ['string', 'null'])
                    ->where('archived_at', fn (?string $archivedAt) => Carbon::make($archivedAt)?->isValid() ?? true),
                default => $this,
            };
        });
    }
}
