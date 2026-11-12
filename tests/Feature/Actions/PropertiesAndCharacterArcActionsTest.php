<?php

namespace Tests\Feature\Actions;

use App\Actions\Archivist\Characters\CreateCharacterArcFacet;
use App\Actions\Archivist\Characters\DeleteCharacterArcFacet;
use App\Actions\Archivist\Characters\GetCharacter;
use App\Actions\Archivist\Characters\GetCharacterArc;
use App\Actions\Archivist\Characters\ReviewCharacterArcRun;
use App\Actions\Archivist\Characters\UpdateCharacterArcFacet;
use App\Actions\Archivist\Properties\UpdateEntityProperties;
use App\Actions\Archivist\Quests\UpdateQuest;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Characters\GetCharacterTool;
use App\Mcp\Tools\Sessions\PatchSessionTool;
use App\Services\ArchivistClient;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

final class PropertiesAndCharacterArcActionsTest extends FeatureTestCase
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
    public function get_character_with_properties_merges_the_property_tables(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/characters/char_1' => Http::response($this->fakeCharacter(), 200),
            self::BASE_URL.'/v1/entities/char_1/properties*' => Http::response($this->fakeProperties(), 200),
        ]);

        $result = GetCharacter::make()->execute(['character_id' => 'char_1', 'with_properties' => true]);

        Http::assertSent(fn (Request $request) => $request->url() === self::BASE_URL.'/v1/characters/char_1');
        Http::assertSent(fn (Request $request) => str_starts_with($request->url(), self::BASE_URL.'/v1/entities/char_1/properties?')
            && $request['campaign_id'] === 'camp_1'
            && $request['type'] === 'characters');
        $this->assertSame('memberships', $result->get('properties')[0]['fields'][0]['key']);
        $this->assertSame('Alexei', $result->get('player_name'));
    }

    #[Test]
    public function get_character_without_properties_makes_one_call(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/characters/char_1*' => Http::response($this->fakeCharacter(), 200),
        ]);

        $result = GetCharacter::make()->execute(['character_id' => 'char_1']);

        Http::assertSentCount(1);
        $this->assertNull($result->get('properties'));
    }

    #[Test]
    public function update_entity_properties_patches_a_partial_object(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/entities/char_1/properties*' => Http::response($this->fakeProperties(), 200),
        ]);

        UpdateEntityProperties::make()->execute([
            'campaign_id' => 'camp_1',
            'entity_type' => 'characters',
            'entity_id' => 'char_1',
            'properties' => [
                'class' => 'Barbarian',
                'alignment' => null,
                'memberships' => ['add' => ['fac_1']],
            ],
        ]);

        Http::assertSent(function (Request $request) {
            return $request->method() === 'PATCH'
                && $request->url() === self::BASE_URL.'/v1/entities/char_1/properties?campaign_id=camp_1&type=characters'
                && $request['properties'] === [
                    'class' => 'Barbarian',
                    'alignment' => null, // nested nulls survive (they clear the field)
                    'memberships' => ['add' => ['fac_1']],
                ];
        });
    }

    #[Test]
    public function character_arc_actions_hit_the_arc_endpoints(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/characters/char_1/arc?status=pending' => Http::response([
                'character_id' => 'char_1',
                'themes' => [['id' => 't1', 'key' => 'Relationships', 'title' => 'Relationships', 'description' => null, 'facets' => [$this->fakeFacet(['status' => 'pending', 'run_id' => 'run_1'])]]],
            ], 200),
            self::BASE_URL.'/v1/characters/char_1/arc/facets' => Http::response($this->fakeFacet(), 201),
            self::BASE_URL.'/v1/characters/char_1/arc/facets/facet_1' => Http::sequence()
                ->push($this->fakeFacet(['id' => 'facet_2', 'replaces_id' => 'facet_1']), 200)
                ->push(['deleted_id' => 'facet_1', 'restored_id' => null], 200),
            self::BASE_URL.'/v1/characters/char_1/arc/runs/run_1/review' => Http::response(
                ['run_id' => 'run_1', 'action' => 'approve', 'affected' => 3, 'restored_ids' => []], 200,
            ),
        ]);

        $arc = GetCharacterArc::make()->execute(['character_id' => 'char_1', 'status' => 'pending']);
        $this->assertSame('run_1', $arc->get('themes')[0]['facets'][0]['run_id']);

        CreateCharacterArcFacet::make()->execute([
            'character_id' => 'char_1',
            'theme' => 'Relationships',
            'title' => 'Ally',
            'description' => 'Swore an oath to the party',
            'session_id' => 'sess_10',
        ]);
        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && $request->url() === self::BASE_URL.'/v1/characters/char_1/arc/facets'
            && $request['theme'] === 'Relationships'
            && $request['session_id'] === 'sess_10');

        $updated = UpdateCharacterArcFacet::make()->execute([
            'character_id' => 'char_1',
            'facet_id' => 'facet_1',
            'description' => 'Swore a blood oath',
        ]);
        $this->assertSame('facet_2', $updated->get('id'));
        Http::assertSent(fn (Request $request) => $request->method() === 'PATCH'
            && $request->data() === ['description' => 'Swore a blood oath']);

        $deleted = DeleteCharacterArcFacet::make()->execute(['character_id' => 'char_1', 'facet_id' => 'facet_1']);
        $this->assertSame('facet_1', $deleted->get('deleted_id'));

        $review = ReviewCharacterArcRun::make()->execute(['character_id' => 'char_1', 'run_id' => 'run_1', 'action' => 'approve']);
        $this->assertSame(3, $review->get('affected'));
        Http::assertSent(fn (Request $request) => $request->url() === self::BASE_URL.'/v1/characters/char_1/arc/runs/run_1/review'
            && $request['action'] === 'approve');
    }

    #[Test]
    public function update_quest_forwards_progress_entries_with_ids_and_sessions(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/quests/quest_1' => Http::response($this->fakeQuest(), 200),
        ]);

        UpdateQuest::make()->execute([
            'quest_id' => 'quest_1',
            'objectives' => [['id' => 'obj_1', 'text' => 'Find the sword', 'status' => 'completed']],
            'progress_log' => [
                ['id' => 'log_1', 'text' => 'Fixed typo', 'session_id' => 'sess_2'],
                ['text' => 'A new entry'],
            ],
        ]);

        Http::assertSent(fn (Request $request) => $request['progress_log'] === [
            ['id' => 'log_1', 'text' => 'Fixed typo', 'session_id' => 'sess_2'],
            ['text' => 'A new entry'],
        ] && $request['objectives'][0]['id'] === 'obj_1');
    }

    #[Test]
    public function write_timeouts_say_the_change_may_have_been_applied(): void
    {
        Http::fake(fn () => throw new ConnectionException('cURL error 28: Operation timed out after 30002 milliseconds with 0 bytes received'));

        $response = ArchivistServer::tool(PatchSessionTool::class, ['session_id' => 'sess_1', 'summary' => 'x']);

        $response->assertHasErrors(['may still have been applied']);
    }

    #[Test]
    public function read_timeouts_say_retry_is_safe(): void
    {
        Http::fake(fn () => throw new ConnectionException('cURL error 28: Operation timed out'));

        $response = ArchivistServer::tool(GetCharacterTool::class, ['character_id' => 'char_1']);

        $response->assertHasErrors(['safe to retry']);
    }

    private function fakeCharacter(): array
    {
        return [
            'id' => 'char_1',
            'campaign_id' => 'camp_1',
            'character_name' => 'Гнашик',
            'character_alias' => null,
            'character_aliases' => [],
            'player_name' => 'Alexei',
            'description' => null,
            'backstory' => null,
            'type' => 'PC',
            'image' => null,
            'tcg_image' => null,
            'merge' => false,
            'created_at' => '2026-10-01T00:00:00',
            'updated_at' => '2026-10-01T00:00:00',
        ];
    }

    private function fakeProperties(): array
    {
        return [
            'entity_id' => 'char_1',
            'entity_type' => 'Character',
            'compendium_entity_type' => 'PC',
            'tables' => [[
                'table_type' => 'CONNECTION',
                'label' => 'Connections',
                'fields' => [[
                    'id' => 'def_1',
                    'key' => 'memberships',
                    'label' => 'Memberships',
                    'field_type' => 'MULTISELECT_CONNECTION',
                    'connection_types' => ['FACTION'],
                    'value' => ['Hellriders'],
                    'connections' => [['id' => 'fac_1', 'name' => 'Hellriders', 'type' => 'FACTION']],
                ]],
            ]],
        ];
    }

    private function fakeFacet(array $overrides = []): array
    {
        return array_merge([
            'id' => 'facet_1',
            'title' => 'Ally',
            'description' => 'Swore an oath to the party',
            'session_id' => 'sess_10',
            'session_number' => 10,
            'version_count' => 0,
            'hidden' => false,
            'status' => 'approved',
            'run_id' => null,
            'source' => 'user',
            'replaces_id' => null,
            'previous_title' => null,
            'previous_description' => null,
            'created_at' => '2026-11-11T00:00:00',
            'updated_at' => '2026-11-11T00:00:00',
        ], $overrides);
    }

    private function fakeQuest(): array
    {
        return [
            'id' => 'quest_1',
            'campaign_id' => 'camp_1',
            'order_index' => 0,
            'quest_name' => 'Recover the Sunblade',
            'quest_category' => 'main',
            'status' => 'in-progress',
            'objectives' => [],
            'progress_log' => [],
            'progress_log_entries' => [],
            'created_at' => '2024-01-01',
            'updated_at' => '2024-01-01',
        ];
    }
}
