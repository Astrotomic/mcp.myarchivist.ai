<?php

namespace App\Mcp\Tools\Concerns;

use App\Mcp\Data\BeatData;
use App\Mcp\Data\CampaignData;
use App\Mcp\Data\CampaignStatsData;
use App\Mcp\Data\CastAnalysisData;
use App\Mcp\Data\CharacterData;
use App\Mcp\Data\FactionData;
use App\Mcp\Data\ItemData;
use App\Mcp\Data\JournalData;
use App\Mcp\Data\JournalFolderData;
use App\Mcp\Data\LinkData;
use App\Mcp\Data\LocationData;
use App\Mcp\Data\MomentData;
use App\Mcp\Data\QuestData;
use App\Mcp\Data\SessionData;
use App\Mcp\Tools\Beats\GetBeatTool;
use App\Mcp\Tools\Beats\ListBeatsTool;
use App\Mcp\Tools\Campaigns\GetCampaignStatsTool;
use App\Mcp\Tools\Campaigns\GetCampaignTool;
use App\Mcp\Tools\Campaigns\ListCampaignsTool;
use App\Mcp\Tools\Characters\GetCharacterTool;
use App\Mcp\Tools\Characters\ListCharactersTool;
use App\Mcp\Tools\Factions\GetFactionTool;
use App\Mcp\Tools\Factions\ListFactionsTool;
use App\Mcp\Tools\Items\GetItemTool;
use App\Mcp\Tools\Items\ListItemsTool;
use App\Mcp\Tools\Journals\GetJournalFolderTool;
use App\Mcp\Tools\Journals\GetJournalTool;
use App\Mcp\Tools\Journals\ListJournalFoldersTool;
use App\Mcp\Tools\Journals\ListJournalsTool;
use App\Mcp\Tools\Links\ListLinksTool;
use App\Mcp\Tools\Locations\GetLocationTool;
use App\Mcp\Tools\Locations\ListLocationsTool;
use App\Mcp\Tools\Moments\GetMomentTool;
use App\Mcp\Tools\Moments\ListMomentsTool;
use App\Mcp\Tools\Quests\GetQuestTool;
use App\Mcp\Tools\Quests\ListQuestsTool;
use App\Mcp\Tools\Sessions\GetSessionCastAnalysisTool;
use App\Mcp\Tools\Sessions\GetSessionTool;
use App\Mcp\Tools\Sessions\ListSessionsTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;

trait HasArchivistOutputSchema
{
    /**
     * Get the tool's output schema.
     *
     * @return array<string, Type>
     */
    public function outputSchema(JsonSchema $schema): array
    {
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

        return $this->dtoOutputSchema($schema, $dtoClass);
    }

    /**
     * @return class-string
     */
    private function outputDtoClass(): string
    {
        return match (static::class) {
            ListCampaignsTool::class, GetCampaignTool::class => CampaignData::class,
            GetCampaignStatsTool::class => CampaignStatsData::class,
            ListCharactersTool::class, GetCharacterTool::class => CharacterData::class,
            ListSessionsTool::class, GetSessionTool::class => SessionData::class,
            GetSessionCastAnalysisTool::class => CastAnalysisData::class,
            ListBeatsTool::class, GetBeatTool::class => BeatData::class,
            ListMomentsTool::class, GetMomentTool::class => MomentData::class,
            ListFactionsTool::class, GetFactionTool::class => FactionData::class,
            ListLocationsTool::class, GetLocationTool::class => LocationData::class,
            ListItemsTool::class, GetItemTool::class => ItemData::class,
            ListQuestsTool::class, GetQuestTool::class => QuestData::class,
            ListJournalsTool::class, GetJournalTool::class => JournalData::class,
            ListJournalFoldersTool::class, GetJournalFolderTool::class => JournalFolderData::class,
            ListLinksTool::class => LinkData::class,
        };
    }

    private function hasListOutput(): bool
    {
        return str_starts_with(class_basename(static::class), 'List');
    }

    /**
     * @param  class-string  $dtoClass
     * @return array<string, Type>
     */
    private function dtoOutputSchema(JsonSchema $schema, string $dtoClass): array
    {
        return match ($dtoClass) {
            CampaignData::class => [
                'id' => $schema->string()->description('Campaign ID.')->required(),
                'title' => $schema->string()->description('Campaign title.')->required(),
                'public' => $schema->boolean()->description('Whether the campaign is public.')->required(),
                'created_at' => $schema->string()->description('Campaign creation timestamp.')->required(),
            ],
            CampaignStatsData::class => [
                'campaignId' => $schema->string()->description('Campaign ID.')->required(),
                'characters' => $schema->integer()->description('Character count.')->required(),
                'sessions' => $schema->integer()->description('Session count.')->required(),
                'moments' => $schema->integer()->description('Moment count.')->required(),
                'beats' => $schema->integer()->description('Beat count.')->required(),
                'factions' => $schema->integer()->description('Faction count.')->required(),
                'locations' => $schema->integer()->description('Location count.')->required(),
                'items' => $schema->integer()->description('Item count.')->required(),
            ],
            CharacterData::class => [
                'id' => $schema->string()->description('Character ID.')->required(),
                'campaign_id' => $schema->string()->description('Campaign ID.')->required(),
                'character_name' => $schema->string()->description('Character name.')->required(),
                'approved' => $schema->boolean()->description('Whether the character is approved.')->required(),
                'created_at' => $schema->string()->description('Character creation timestamp.')->required(),
            ],
            SessionData::class => [
                'id' => $schema->string()->description('Session ID.')->required(),
                'campaign_id' => $schema->string()->description('Campaign ID.')->required(),
                'public' => $schema->boolean()->description('Whether the session is public.')->required(),
                'created_at' => $schema->string()->description('Session creation timestamp.')->required(),
            ],
            CastAnalysisData::class => [
                'id' => $schema->string()->description('Cast analysis ID.')->required(),
                'session_id' => $schema->string()->description('Session ID.')->required(),
                'analysis' => $schema->object()->description('Cast analysis metrics and breakdowns.')->required(),
                'created_at' => $schema->string()->description('Creation timestamp.')->required(),
                'updated_at' => $schema->string()->description('Last update timestamp.')->required(),
            ],
            BeatData::class => [
                'id' => $schema->string()->description('Beat ID.')->required(),
                'campaign_id' => $schema->string()->description('Campaign ID.')->required(),
                'label' => $schema->string()->description('Beat label.')->required(),
                'type' => $schema->string()->enum(['major', 'minor', 'step'])->description('Beat type.')->required(),
                'index' => $schema->integer()->description('Beat ordering index.')->required(),
                'created_at' => $schema->string()->description('Beat creation timestamp.')->required(),
            ],
            MomentData::class => [
                'id' => $schema->string()->description('Moment ID.')->required(),
                'campaign_id' => $schema->string()->description('Campaign ID.')->required(),
                'label' => $schema->string()->description('Moment label.')->required(),
                'created_at' => $schema->string()->description('Moment creation timestamp.')->required(),
            ],
            FactionData::class => [
                'id' => $schema->string()->description('Faction ID.')->required(),
                'campaign_id' => $schema->string()->description('Campaign ID.')->required(),
                'name' => $schema->string()->description('Faction name.')->required(),
                'created_at' => $schema->string()->description('Faction creation timestamp.')->required(),
            ],
            LocationData::class => [
                'id' => $schema->string()->description('Location ID.')->required(),
                'campaign_id' => $schema->string()->description('Campaign ID.')->required(),
                'name' => $schema->string()->description('Location name.')->required(),
                'created_at' => $schema->string()->description('Location creation timestamp.')->required(),
            ],
            ItemData::class => [
                'id' => $schema->string()->description('Item ID.')->required(),
                'campaign_id' => $schema->string()->description('Campaign ID.')->required(),
                'name' => $schema->string()->description('Item name.')->required(),
                'created_at' => $schema->string()->description('Item creation timestamp.')->required(),
            ],
            QuestData::class => [
                'id' => $schema->string()->description('Quest ID.')->required(),
                'campaign_id' => $schema->string()->description('Campaign ID.')->required(),
                'order_index' => $schema->integer()->description('Quest ordering index.')->required(),
                'quest_name' => $schema->string()->description('Quest name.')->required(),
                'quest_category' => $schema->string()->enum(['main', 'side', 'faction', 'personal', 'n/a'])->description('Quest category.'),
                'status' => $schema->string()->enum(['planned', 'in-progress', 'blocked', 'failed', 'done', 'n/a'])->description('Quest status.'),
                'created_at' => $schema->string()->description('Quest creation timestamp.')->required(),
                'updated_at' => $schema->string()->description('Last update timestamp.')->required(),
            ],
            JournalData::class => [
                'id' => $schema->string()->description('Journal entry ID.')->required(),
                'world_id' => $schema->string()->description('World ID.')->required(),
                'title' => $schema->string()->description('Journal title.')->required(),
                'is_public' => $schema->boolean()->description('Whether the journal entry is public.')->required(),
                'created_at' => $schema->string()->description('Journal creation timestamp.')->required(),
                'updated_at' => $schema->string()->description('Last update timestamp.')->required(),
            ],
            JournalFolderData::class => [
                'id' => $schema->string()->description('Journal folder ID.')->required(),
                'world_id' => $schema->string()->description('World ID.')->required(),
                'name' => $schema->string()->description('Folder name.')->required(),
                'path' => $schema->string()->description('Folder path.')->required(),
                'position' => $schema->integer()->description('Folder position.')->required(),
                'created_at' => $schema->string()->description('Folder creation timestamp.')->required(),
                'updated_at' => $schema->string()->description('Last update timestamp.')->required(),
            ],
            LinkData::class => [
                'id' => $schema->string()->description('Link ID.')->required(),
                'campaign_id' => $schema->string()->description('Campaign ID.')->required(),
                'from_id' => $schema->string()->description('Source entity ID.')->required(),
                'from_type' => $schema->string()->description('Source entity type.')->required(),
                'to_id' => $schema->string()->description('Target entity ID.')->required(),
                'to_type' => $schema->string()->description('Target entity type.')->required(),
                'created_at' => $schema->string()->description('Link creation timestamp.')->required(),
            ],
        };
    }

    /**
     * @param  class-string  $dtoClass
     */
    private function outputEntityDescription(string $dtoClass): string
    {
        return match ($dtoClass) {
            CampaignData::class => 'campaign',
            CampaignStatsData::class => 'campaign statistics',
            CharacterData::class => 'character',
            SessionData::class => 'session',
            CastAnalysisData::class => 'cast analysis',
            BeatData::class => 'beat',
            MomentData::class => 'moment',
            FactionData::class => 'faction',
            LocationData::class => 'location',
            ItemData::class => 'item',
            QuestData::class => 'quest',
            JournalData::class => 'journal',
            JournalFolderData::class => 'journal folder',
            LinkData::class => 'entity link',
        };
    }
}
