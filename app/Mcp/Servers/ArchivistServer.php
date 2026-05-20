<?php

namespace App\Mcp\Servers;

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
use App\Mcp\Tools\Sessions\GetSessionHandoutTool;
use App\Mcp\Tools\Sessions\GetSessionTool;
use App\Mcp\Tools\Sessions\ListSessionsTool;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('Archivist AI')]
#[Version('1.0.0')]
#[Instructions('Read-only access to Archivist AI TTRPG campaign data: campaigns, characters, sessions, beats, moments, factions, locations, items, quests, journals, journal folders, and entity links. Archivist AI is a campaign memory platform for tabletop RPG game masters and players. All tools are read-only (v1). Mutating operations are deferred to v2.')]
class ArchivistServer extends Server
{
    protected array $tools = [
        // Campaigns
        ListCampaignsTool::class,
        GetCampaignTool::class,
        GetCampaignStatsTool::class,

        // Characters
        ListCharactersTool::class,
        GetCharacterTool::class,

        // Sessions
        ListSessionsTool::class,
        GetSessionTool::class,
        GetSessionCastAnalysisTool::class,
        GetSessionHandoutTool::class,

        // Beats
        ListBeatsTool::class,
        GetBeatTool::class,

        // Moments
        ListMomentsTool::class,
        GetMomentTool::class,

        // Factions
        ListFactionsTool::class,
        GetFactionTool::class,

        // Locations
        ListLocationsTool::class,
        GetLocationTool::class,

        // Items
        ListItemsTool::class,
        GetItemTool::class,

        // Quests
        ListQuestsTool::class,
        GetQuestTool::class,

        // Journals
        ListJournalsTool::class,
        GetJournalTool::class,

        // Journal Folders
        ListJournalFoldersTool::class,
        GetJournalFolderTool::class,

        // Links
        ListLinksTool::class,
    ];
}
