<?php

namespace Tests\Feature\Actions;

use App\Actions\Archivist\Beats\CreateBeat;
use App\Actions\Archivist\Beats\DeleteBeat;
use App\Actions\Archivist\Campaigns\CreateCampaign;
use App\Actions\Archivist\Campaigns\UpdateCampaign;
use App\Actions\Archivist\Characters\CreateCharacter;
use App\Actions\Archivist\Characters\DeleteCharacter;
use App\Actions\Archivist\Characters\UpdateCharacter;
use App\Actions\Archivist\Factions\CreateFaction;
use App\Actions\Archivist\Items\CreateItem;
use App\Actions\Archivist\Journals\CreateJournal;
use App\Actions\Archivist\Journals\DeleteJournal;
use App\Actions\Archivist\Journals\UpdateJournal;
use App\Actions\Archivist\Links\BulkLinkMaintenance;
use App\Actions\Archivist\Links\CreateLink;
use App\Actions\Archivist\Links\DeleteLink;
use App\Actions\Archivist\Links\UpdateLink;
use App\Actions\Archivist\Locations\CreateLocation;
use App\Actions\Archivist\Moments\CreateMoment;
use App\Actions\Archivist\Quests\CreateQuest;
use App\Actions\Archivist\Sessions\PatchSession;
use App\Actions\Archivist\Sessions\UpdateSession;
use App\Data\LinkMaintenanceResultData;
use App\Data\SuccessData;
use App\Services\ArchivistClient;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

final class WriteActionsTest extends FeatureTestCase
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
    public function create_campaign_posts_json_to_campaigns_endpoint(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/campaigns' => Http::response($this->fakeCampaign(), 201),
        ]);

        $result = CreateCampaign::make()->execute([
            'title' => 'The Long Winter',
            'description' => 'A frigid campaign.',
            'system' => 'D&D 5e',
        ]);

        Http::assertSent(function (Request $request) {
            return $request->method() === 'POST'
                && $request->url() === self::BASE_URL.'/v1/campaigns'
                && $request['title'] === 'The Long Winter'
                && $request['description'] === 'A frigid campaign.'
                && $request['system'] === 'D&D 5e';
        });

        $this->assertSame('camp_1', $result->get('id'));
    }

    #[Test]
    public function update_campaign_patches_the_target_campaign(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/campaigns/camp_1' => Http::response($this->fakeCampaign(), 200),
        ]);

        UpdateCampaign::make()->execute([
            'campaign_id' => 'camp_1',
            'title' => 'Renamed',
        ]);

        Http::assertSent(function (Request $request) {
            return $request->method() === 'PATCH'
                && $request->url() === self::BASE_URL.'/v1/campaigns/camp_1'
                && $request['title'] === 'Renamed'
                && ! array_key_exists('campaign_id', $request->data());
        });
    }

    #[Test]
    public function create_character_sends_description_wikilinks_verbatim(): void
    {
        $description = 'A knight sworn to [[Queen Alice|Her Majesty]] and rivals with [[Duke Bob]].';

        Http::fake([
            self::BASE_URL.'/v1/characters' => Http::response($this->fakeCharacter($description), 201),
        ]);

        CreateCharacter::make()->execute([
            'campaign_id' => 'camp_1',
            'character_name' => 'Sir Cedric',
            'description' => $description,
            'type' => 'NPC',
        ]);

        Http::assertSent(fn (Request $request) => $request['description'] === $description);
    }

    #[Test]
    public function update_character_does_not_mangle_wikilinks_in_body(): void
    {
        $original = 'Rumoured heir of [[House Vale]].';

        Http::fake([
            self::BASE_URL.'/v1/characters/char_1' => Http::response($this->fakeCharacter($original), 200),
        ]);

        UpdateCharacter::make()->execute([
            'character_id' => 'char_1',
            'description' => $original,
        ]);

        Http::assertSent(function (Request $request) use ($original) {
            return $request->method() === 'PATCH'
                && $request['description'] === $original;
        });
    }

    #[Test]
    public function delete_character_returns_success_data_from_204_response(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/characters/char_1' => Http::response(status: 204),
        ]);

        $result = DeleteCharacter::make()->execute(['character_id' => 'char_1']);

        $this->assertInstanceOf(SuccessData::class, $result);
        $this->assertTrue((bool) $result->get('success'));
        $this->assertSame('char_1', $result->get('id'));

        Http::assertSent(fn (Request $request) => $request->method() === 'DELETE');
    }

    #[Test]
    public function create_faction_posts_to_factions_endpoint(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/factions' => Http::response($this->fakeFaction(), 201),
        ]);

        CreateFaction::make()->execute([
            'campaign_id' => 'camp_1',
            'name' => 'Silver Compass',
            'description' => 'Guild allied with [[Queen Alice]].',
        ]);

        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && $request->url() === self::BASE_URL.'/v1/factions'
            && $request['description'] === 'Guild allied with [[Queen Alice]].');
    }

    #[Test]
    public function create_location_posts_to_locations_endpoint(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/locations' => Http::response($this->fakeLocation(), 201),
        ]);

        CreateLocation::make()->execute([
            'campaign_id' => 'camp_1',
            'name' => 'The Iron Keep',
        ]);

        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && $request->url() === self::BASE_URL.'/v1/locations');
    }

    #[Test]
    public function create_item_posts_to_items_endpoint(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/items' => Http::response($this->fakeItem(), 201),
        ]);

        CreateItem::make()->execute([
            'campaign_id' => 'camp_1',
            'name' => 'Sunblade',
        ]);

        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && $request->url() === self::BASE_URL.'/v1/items');
    }

    #[Test]
    public function create_moment_posts_to_moments_endpoint(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/moments' => Http::response($this->fakeMoment(), 201),
        ]);

        CreateMoment::make()->execute([
            'campaign_id' => 'camp_1',
            'session_id' => 'sess_1',
            'label' => 'Fireside chat',
            'content' => 'The party regrouped at camp.',
        ]);

        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && $request->url() === self::BASE_URL.'/v1/moments'
            && $request['session_id'] === 'sess_1');
    }

    #[Test]
    public function patch_session_sends_summary_verbatim(): void
    {
        $summary = 'The party met [[Queen Alice]] outside [[The Iron Keep]].';

        Http::fake([
            self::BASE_URL.'/v1/sessions/sess_1' => Http::response($this->fakeSession($summary), 200),
        ]);

        PatchSession::make()->execute([
            'session_id' => 'sess_1',
            'summary' => $summary,
        ]);

        Http::assertSent(fn (Request $request) => $request->method() === 'PATCH'
            && $request['summary'] === $summary
            && ! array_key_exists('session_id', $request->data()));
    }

    #[Test]
    public function update_session_puts_to_target_session(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/sessions/sess_1' => Http::response($this->fakeSession(), 200),
        ]);

        UpdateSession::make()->execute([
            'session_id' => 'sess_1',
            'title' => 'The Reckoning',
        ]);

        Http::assertSent(fn (Request $request) => $request->method() === 'PUT'
            && $request->url() === self::BASE_URL.'/v1/sessions/sess_1');
    }

    #[Test]
    public function create_beat_posts_to_beats_endpoint(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/beats' => Http::response($this->fakeBeat(), 201),
        ]);

        CreateBeat::make()->execute([
            'campaign_id' => 'camp_1',
            'label' => 'Trial of Iron',
        ]);

        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && $request->url() === self::BASE_URL.'/v1/beats');
    }

    #[Test]
    public function delete_beat_returns_success_data(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/beats/beat_1' => Http::response(status: 204),
        ]);

        $result = DeleteBeat::make()->execute(['beat_id' => 'beat_1']);

        $this->assertInstanceOf(SuccessData::class, $result);
        $this->assertSame('beat_1', $result->get('id'));
    }

    #[Test]
    public function create_quest_posts_to_quests_endpoint(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/quests' => Http::response($this->fakeQuest(), 201),
        ]);

        CreateQuest::make()->execute([
            'campaign_id' => 'camp_1',
            'quest_name' => 'Recover the Sunblade',
            'status' => 'in-progress',
        ]);

        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && $request->url() === self::BASE_URL.'/v1/quests'
            && $request['status'] === 'in-progress');
    }

    #[Test]
    public function create_journal_returns_success_data_with_id(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/journals' => Http::response(['success' => true, 'id' => 'j_1'], 201),
        ]);

        $result = CreateJournal::make()->execute([
            'campaign_id' => 'camp_1',
            'title' => 'Session Recap',
            'content' => 'The party met [[Queen Alice]].',
        ]);

        $this->assertInstanceOf(SuccessData::class, $result);
        $this->assertSame('j_1', $result->get('id'));
        $this->assertTrue((bool) $result->get('success'));

        Http::assertSent(fn (Request $request) => $request['content'] === 'The party met [[Queen Alice]].');
    }

    #[Test]
    public function update_journal_puts_body_with_id(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/journals' => Http::response(['success' => true, 'id' => 'j_1'], 200),
        ]);

        UpdateJournal::make()->execute([
            'entry_id' => 'j_1',
            'title' => 'Updated Recap',
        ]);

        Http::assertSent(function (Request $request) {
            return $request->method() === 'PUT'
                && $request->url() === self::BASE_URL.'/v1/journals'
                && $request['id'] === 'j_1'
                && $request['title'] === 'Updated Recap'
                && ! array_key_exists('entry_id', $request->data());
        });
    }

    #[Test]
    public function delete_journal_sends_id_as_query_string(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/journals*' => Http::response(['success' => true], 200),
        ]);

        DeleteJournal::make()->execute(['entry_id' => 'j_1']);

        Http::assertSent(function (Request $request) {
            return $request->method() === 'DELETE'
                && str_starts_with($request->url(), self::BASE_URL.'/v1/journals?')
                && str_contains($request->url(), 'id=j_1');
        });
    }

    #[Test]
    public function create_link_posts_to_campaign_links_endpoint(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/campaigns/camp_1/links' => Http::response($this->fakeLink(), 201),
        ]);

        CreateLink::make()->execute([
            'campaign_id' => 'camp_1',
            'from_id' => 'char_1',
            'from_type' => 'Character',
            'to_id' => 'char_2',
            'to_type' => 'Character',
            'alias' => 'the Queen',
        ]);

        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && $request->url() === self::BASE_URL.'/v1/campaigns/camp_1/links'
            && $request['alias'] === 'the Queen'
            && $request['from_type'] === 'Character');
    }

    #[Test]
    public function update_link_patches_the_link(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/campaigns/camp_1/links/link_1' => Http::response($this->fakeLink(), 200),
        ]);

        UpdateLink::make()->execute([
            'campaign_id' => 'camp_1',
            'link_id' => 'link_1',
            'alias' => 'the Sovereign',
        ]);

        Http::assertSent(fn (Request $request) => $request->method() === 'PATCH'
            && $request['alias'] === 'the Sovereign'
            && ! array_key_exists('campaign_id', $request->data())
            && ! array_key_exists('link_id', $request->data()));
    }

    #[Test]
    public function delete_link_returns_success_data_with_link_id(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/campaigns/camp_1/links/link_1' => Http::response(status: 204),
        ]);

        $result = DeleteLink::make()->execute([
            'campaign_id' => 'camp_1',
            'link_id' => 'link_1',
        ]);

        $this->assertInstanceOf(SuccessData::class, $result);
        $this->assertSame('link_1', $result->get('id'));
    }

    #[Test]
    public function bulk_link_maintenance_add_dispatches_to_correct_target_endpoint(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/characters/links/maintenance' => Http::response(
                ['success' => true, 'taskId' => 'task_42'],
                202,
            ),
        ]);

        $result = BulkLinkMaintenance::make()->execute([
            'operation' => 'add',
            'campaign_id' => 'camp_1',
            'target_id' => 'char_1',
            'target_type' => 'Character',
            'alias' => 'the King',
        ]);

        $this->assertInstanceOf(LinkMaintenanceResultData::class, $result);
        $this->assertSame('task_42', $result->get('task_id'));

        Http::assertSent(function (Request $request) {
            return $request->method() === 'POST'
                && $request->url() === self::BASE_URL.'/v1/characters/links/maintenance'
                && $request['operation'] === 'add'
                && $request['worldId'] === 'camp_1'
                && $request['targetId'] === 'char_1'
                && $request['targetType'] === 'Character'
                && $request['alias'] === 'the King';
        });
    }

    #[Test]
    public function bulk_link_maintenance_update_uses_new_alias(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/factions/links/maintenance' => Http::response(
                ['success' => true, 'taskId' => 'task_9'],
                202,
            ),
        ]);

        BulkLinkMaintenance::make()->execute([
            'operation' => 'update',
            'campaign_id' => 'camp_1',
            'target_id' => 'fac_1',
            'target_type' => 'Faction',
            'new_alias' => 'the Circle',
        ]);

        Http::assertSent(function (Request $request) {
            return $request->method() === 'POST'
                && $request->url() === self::BASE_URL.'/v1/factions/links/maintenance'
                && $request['operation'] === 'update'
                && $request['newAlias'] === 'the Circle'
                && ! array_key_exists('targetType', $request->data())
                && ! array_key_exists('alias', $request->data());
        });
    }

    private function fakeCampaign(array $overrides = []): array
    {
        return array_replace([
            'id' => 'camp_1',
            'title' => 'The Long Winter',
            'description' => 'A frigid campaign.',
            'system' => 'D&D 5e',
            'summary' => null,
            'language' => 'en',
            'public' => false,
            'mature' => false,
            'owner_id' => '00000000-0000-0000-0000-000000000001',
            'image' => null,
            'archived' => false,
            'archived_at' => null,
            'created_at' => '2024-01-01',
            'updated_at' => '2024-01-01',
        ], $overrides);
    }

    private function fakeCharacter(?string $description = null): array
    {
        return [
            'id' => 'char_1',
            'campaign_id' => 'camp_1',
            'character_name' => 'Sir Cedric',
            'character_alias' => null,
            'player_name' => null,
            'player' => null,
            'description' => $description,
            'image' => null,
            'type' => 'NPC',
            'character_aliases' => null,
            'player_handle' => null,
            'backstory' => null,
            'tcg_image' => null,
            'merge' => false,
            'created_at' => '2024-01-01',
            'updated_at' => '2024-01-01',
        ];
    }

    private function fakeFaction(): array
    {
        return [
            'id' => 'fac_1',
            'campaign_id' => 'camp_1',
            'name' => 'Silver Compass',
            'description' => null,
            'type' => null,
            'image' => null,
            'aliases' => [],
            'tcg_image' => null,
            'merge' => false,
            'created_at' => '2024-01-01',
            'updated_at' => '2024-01-01',
        ];
    }

    private function fakeLocation(): array
    {
        return [
            'id' => 'loc_1',
            'campaign_id' => 'camp_1',
            'name' => 'The Iron Keep',
            'description' => null,
            'type' => null,
            'parent_id' => null,
            'image' => null,
            'aliases' => [],
            'tcg_image' => null,
            'merge' => false,
            'created_at' => '2024-01-01',
            'updated_at' => '2024-01-01',
        ];
    }

    private function fakeItem(): array
    {
        return [
            'id' => 'itm_1',
            'campaign_id' => 'camp_1',
            'name' => 'Sunblade',
            'description' => null,
            'type' => null,
            'image' => null,
            'aliases' => [],
            'tcg_image' => null,
            'merge' => false,
            'created_at' => '2024-01-01',
            'updated_at' => '2024-01-01',
        ];
    }

    private function fakeMoment(): array
    {
        return [
            'id' => 'mom_1',
            'campaign_id' => 'camp_1',
            'session_id' => 'sess_1',
            'label' => 'Fireside chat',
            'index' => 0,
            'content' => 'The party regrouped at camp.',
            'image' => null,
            'categories' => [],
            'created_at' => '2024-01-01',
            'updated_at' => '2024-01-01',
        ];
    }

    private function fakeSession(?string $summary = null): array
    {
        return [
            'id' => 'sess_1',
            'campaign_id' => 'camp_1',
            'type' => 'other',
            'title' => 'Session Zero',
            'summary' => $summary,
            'session_date' => '2024-01-01T00:00:00Z',
            'public' => false,
            'notes' => null,
            'image' => null,
            'index' => 0,
            'pbp_start_msg_url' => null,
            'pbp_end_msg_url' => null,
            'created_at' => '2024-01-01',
            'updated_at' => '2024-01-01',
        ];
    }

    private function fakeBeat(): array
    {
        return [
            'id' => 'beat_1',
            'campaign_id' => 'camp_1',
            'game_session_id' => null,
            'game_session_ids' => [],
            'label' => 'Trial of Iron',
            'type' => 'major',
            'index' => 0,
            'parent_id' => null,
            'description' => null,
            'metadata' => null,
            'created_at' => '2024-01-01',
            'updated_at' => '2024-01-01',
        ];
    }

    private function fakeQuest(): array
    {
        return [
            'id' => 'quest_1',
            'campaign_id' => 'camp_1',
            'order_index' => 0,
            'quest_name' => 'Recover the Sunblade',
            'quest_giver' => null,
            'quest_giver_id' => null,
            'quest_category' => 'main',
            'status' => 'in-progress',
            'success_definition' => null,
            'failure_conditions' => null,
            'next_action' => null,
            'resolution' => null,
            'objectives' => [],
            'objective_count' => 0,
            'completed_objective_count' => 0,
            'progress_entry_count' => 0,
            'related_entity_count' => 0,
            'progress_log' => [],
            'progress_log_entries' => [],
            'related_characters' => [],
            'related_factions' => [],
            'related_locations' => [],
            'related_items' => [],
            'related_entity_refs' => [],
            'first_session' => null,
            'last_session' => null,
            'created_at' => '2024-01-01',
            'updated_at' => '2024-01-01',
        ];
    }

    private function fakeLink(): array
    {
        return [
            'id' => 'link_1',
            'campaign_id' => 'camp_1',
            'from_id' => 'char_1',
            'from_type' => 'Character',
            'to_id' => 'char_2',
            'to_type' => 'Character',
            'alias' => 'the Queen',
            'created_at' => '2024-01-01T00:00:00Z',
        ];
    }
}
