<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\Beats\CreateBeatTool;
use App\Mcp\Tools\Beats\DeleteBeatTool;
use App\Mcp\Tools\Beats\GetBeatTool;
use App\Mcp\Tools\Beats\ListBeatsTool;
use App\Mcp\Tools\Beats\UpdateBeatTool;
use App\Mcp\Tools\Campaigns\CreateCampaignTool;
use App\Mcp\Tools\Campaigns\GetCampaignStatsTool;
use App\Mcp\Tools\Campaigns\GetCampaignTool;
use App\Mcp\Tools\Campaigns\ListCampaignsTool;
use App\Mcp\Tools\Campaigns\UpdateCampaignTool;
use App\Mcp\Tools\Characters\CreateCharacterTool;
use App\Mcp\Tools\Characters\DeleteCharacterTool;
use App\Mcp\Tools\Characters\GetCharacterTool;
use App\Mcp\Tools\Characters\ListCharactersTool;
use App\Mcp\Tools\Characters\UpdateCharacterTool;
use App\Mcp\Tools\Factions\CreateFactionTool;
use App\Mcp\Tools\Factions\DeleteFactionTool;
use App\Mcp\Tools\Factions\GetFactionTool;
use App\Mcp\Tools\Factions\ListFactionsTool;
use App\Mcp\Tools\Factions\UpdateFactionTool;
use App\Mcp\Tools\Images\CompleteImageUploadTool;
use App\Mcp\Tools\Images\DeleteEntityImageTool;
use App\Mcp\Tools\Images\GenerateImageTool;
use App\Mcp\Tools\Images\GetImageUsageTool;
use App\Mcp\Tools\Images\InitImageUploadTool;
use App\Mcp\Tools\Items\CreateItemTool;
use App\Mcp\Tools\Items\DeleteItemTool;
use App\Mcp\Tools\Items\GetItemTool;
use App\Mcp\Tools\Items\ListItemsTool;
use App\Mcp\Tools\Items\UpdateItemTool;
use App\Mcp\Tools\Journals\CreateJournalFolderTool;
use App\Mcp\Tools\Journals\CreateJournalTool;
use App\Mcp\Tools\Journals\DeleteJournalFolderTool;
use App\Mcp\Tools\Journals\DeleteJournalTool;
use App\Mcp\Tools\Journals\GetJournalFolderTool;
use App\Mcp\Tools\Journals\GetJournalTool;
use App\Mcp\Tools\Journals\ListJournalFoldersTool;
use App\Mcp\Tools\Journals\ListJournalsTool;
use App\Mcp\Tools\Journals\UpdateJournalFolderTool;
use App\Mcp\Tools\Journals\UpdateJournalTool;
use App\Mcp\Tools\Links\BulkLinkMaintenanceTool;
use App\Mcp\Tools\Links\CreateLinkTool;
use App\Mcp\Tools\Links\DeleteLinkTool;
use App\Mcp\Tools\Links\ListLinksTool;
use App\Mcp\Tools\Links\UpdateLinkTool;
use App\Mcp\Tools\Locations\CreateLocationTool;
use App\Mcp\Tools\Locations\DeleteLocationTool;
use App\Mcp\Tools\Locations\GetLocationTool;
use App\Mcp\Tools\Locations\ListLocationsTool;
use App\Mcp\Tools\Locations\UpdateLocationTool;
use App\Mcp\Tools\Moments\CreateMomentTool;
use App\Mcp\Tools\Moments\DeleteMomentTool;
use App\Mcp\Tools\Moments\GetMomentTool;
use App\Mcp\Tools\Moments\ListMomentsTool;
use App\Mcp\Tools\Moments\UpdateMomentTool;
use App\Mcp\Tools\Quests\CreateQuestTool;
use App\Mcp\Tools\Quests\DeleteQuestTool;
use App\Mcp\Tools\Quests\GetQuestTool;
use App\Mcp\Tools\Quests\ListQuestsTool;
use App\Mcp\Tools\Quests\UpdateQuestTool;
use App\Mcp\Tools\Sessions\GetSessionCastAnalysisTool;
use App\Mcp\Tools\Sessions\GetSessionHandoutTool;
use App\Mcp\Tools\Sessions\GetSessionTool;
use App\Mcp\Tools\Sessions\GetSessionTranscriptTool;
use App\Mcp\Tools\Sessions\ListSessionsTool;
use App\Mcp\Tools\Sessions\PatchSessionTool;
use App\Mcp\Tools\Sessions\UpdateSessionTool;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;
use Laravel\Mcp\Server\Transport\FakeTransporter;

#[Name('Archivist AI')]
#[Version('2.1.0')]
#[Instructions(<<<'INSTRUCTIONS'
Read/write access to Archivist AI TTRPG campaign data: campaigns, characters, sessions, beats, moments, factions, locations, items, quests, journals, journal folders, entity links, and entity images. Archivist AI is a campaign memory platform for tabletop RPG game masters and players.

## Wikilinks (IMPORTANT for write tools)

Textual references between records use `[[Target Name|Optional Alias]]` markup. The API's handling of wikilinks depends on the record type, and getting this wrong on a write will silently destroy existing links. Before editing any text field that carries wikilinks, always:

1. **Read first with `with_links: true`** — every Get/List tool for Character, Faction, Location, Item, Moment, Beat, Session, and Journal now accepts this parameter (default false to preserve legacy behavior). Without it, stored text comes back with wikilinks stripped, so a naive round-trip write erases every link on the source record.

2. **Preserve existing markup** — leave `[[Alice|Al]]` intact unless you intend to change the alias/target. To rename an alias, edit inside the brackets. To repoint at a different entity, change the visible target name (`[[Alice]]` → `[[Alicia]]`). To remove, unbracket.

3. **Understand the per-type contract**:
   - **Compendium entities (Character/Faction/Location/Item + Character.backstory)** — Auto-resolve: the API extracts `[[…]]` from the new text, matches each against entities in the same campaign, and syncs Link rows (inserts/updates/deletes) so the Link table matches the text. New `[[Alias]]` markup for a matchable entity creates a Link automatically. **Important:** on write, the API deletes existing Link rows from this entity when their `alias` no longer appears wrapped in the text — even if those links existed before your edit.
   - **Sessions, Beats, Moments** — Explicit-link only: new `[[Alias]]` markup in `summary`/`description`/`content` is **stripped unless a matching Link already exists** for that source. To add a wikilink, first call `create_link` with the correct `from_type`/`from_id`, then update the text with `[[Alias]]`.
   - **Journals** — Rendering-only: `[[…]]` in stored content is honored on read with `with_links: true`, but writes do not auto-populate the Link table. Use `create_link` (from_type=Journal) if you want persistent link tracking.
   - **Deletes** — When you delete a Character/Faction/Location/Item/Moment, the API automatically unbrackets all `[[alias]]` references to it across every referring record. No manual cleanup is required.

4. **When updating compendium text (description / backstory)** — use this order every time:
   1. Fetch the entity with `with_links: true`.
   2. Call `list_links` with `from_id` = the entity's id and `from_type` = `Character`, `Faction`, `Location`, or `Item`. Store every returned `alias` in memory.
   3. Draft or rewrite the prose.
   4. Re-wrap each stored link `alias` as `[[alias]]` anywhere that alias still appears in the new text (use `[[Target Name|alias]]` only when that link intentionally used a different display label).
   5. **Discovery pass:** for other proper nouns / entity references in the draft, call `list_characters`, `list_factions`, `list_locations`, and/or `list_items` with the `search` parameter — one call per candidate name. Read only `character_name` or `name` (and `aliases` when present) from matches; do not ingest full `description` bodies unless you need detail. Paginate only when search is inconclusive — there is no separate "names only" endpoint.
   6. Wrap confirmed matches per the **Wikilink markup format** rules below. Compendium writes auto-create Link rows — no `create_link` call needed for Character/Faction/Location/Item targets.
   7. Write with the matching update tool.

5. **When creating compendium records from scratch** — after drafting text, run the discovery pass (steps 5–6 above) before the create call, then include all resolved `[[wikilinks]]` in the initial payload.

6. **Wikilink markup format (do not get this wrong)**:
   - In `[[Target Name|Alias]]`, the **left** side is used only to resolve the entity; the **right** side becomes `Link.alias`. If there is no pipe, both sides are the same string and that string is the alias.
   - The UI builds a link map keyed by `Link.alias`. Bracket text must match that alias exactly or the link will not render — even when a Link row looks correct in the database.
   - **Default:** when prose uses an entity's full name, write `[[Exact Name From API]]` with **no pipe**. Copy `character_name` or `name` byte-for-byte from `list_*` results; do not copy typography from surrounding prose (e.g. do not substitute curly apostrophes for straight ones).
   - **Pipe only when the visible label differs from the entity name**, e.g. `[[Queen Whatsherface|the queen]]` where the prose says "the queen".
   - **Never** write `[[Name|Name]]`, never put the "canonical" name on the left and a prose variant on the right for the same entity, and never mix look-alike characters (straight `'` vs curly `'`, etc.) across the pipe.
   - When re-wrapping aliases from `list_links`, the string inside `[[…]]` must match the stored `alias` field exactly.

## Images

Entity images (Character, Faction, Location, Item, Moment, Session, Campaign/World) can be added in two ways:

- **`generate_image`** — Server-side AI generation from the entity's stored description. Supported types: `character`, `faction`, `location`, `item`, `world`. Consumes the account's image quota (check with `get_image_usage`). Returns a public URL; the image is NOT automatically attached — call the entity's update tool with `image` set to the URL if you want to persist it. AI features are disabled on archived campaigns.
- **Direct upload** — Two-step flow. Call `init_image_upload` to receive a presigned S3 PUT URL (`upload_url`), then have your client `PUT` the raw image bytes to that URL with the same `Content-Type` header. Once the PUT succeeds, call `complete_image_upload` (with the returned `object_key`) to run NSFW moderation and, when `attach: true`, set the entity's `image` field to the moderated `public_url`. If your agent cannot perform arbitrary HTTP PUTs, prefer `generate_image` or hand off the PUT to a human collaborator between the two tools.

Use `delete_entity_image` to detach and clean up either by `entity_type` + `entity_id` (detaches AND deletes the object) or by managed `image_url` (deletes just the object).

## Excluded operations

Campaign delete, session create/delete, product-view-only operations (beat reorder/batch-edit, campaign settings, cast/member management), and multipart recording uploads are intentionally not exposed as MCP tools in this version.
INSTRUCTIONS)]
final class ArchivistServer extends Server
{
    public int $maxPaginationLength = 100;

    public int $defaultPaginationLength = 100;

    protected array $tools = [
        // Campaigns
        ListCampaignsTool::class,
        GetCampaignTool::class,
        GetCampaignStatsTool::class,
        CreateCampaignTool::class,
        UpdateCampaignTool::class,

        // Characters
        ListCharactersTool::class,
        GetCharacterTool::class,
        CreateCharacterTool::class,
        UpdateCharacterTool::class,
        DeleteCharacterTool::class,

        // Sessions
        ListSessionsTool::class,
        GetSessionTool::class,
        GetSessionCastAnalysisTool::class,
        GetSessionHandoutTool::class,
        GetSessionTranscriptTool::class,
        PatchSessionTool::class,
        UpdateSessionTool::class,

        // Beats
        ListBeatsTool::class,
        GetBeatTool::class,
        CreateBeatTool::class,
        UpdateBeatTool::class,
        DeleteBeatTool::class,

        // Moments
        ListMomentsTool::class,
        GetMomentTool::class,
        CreateMomentTool::class,
        UpdateMomentTool::class,
        DeleteMomentTool::class,

        // Factions
        ListFactionsTool::class,
        GetFactionTool::class,
        CreateFactionTool::class,
        UpdateFactionTool::class,
        DeleteFactionTool::class,

        // Locations
        ListLocationsTool::class,
        GetLocationTool::class,
        CreateLocationTool::class,
        UpdateLocationTool::class,
        DeleteLocationTool::class,

        // Items
        ListItemsTool::class,
        GetItemTool::class,
        CreateItemTool::class,
        UpdateItemTool::class,
        DeleteItemTool::class,

        // Quests
        ListQuestsTool::class,
        GetQuestTool::class,
        CreateQuestTool::class,
        UpdateQuestTool::class,
        DeleteQuestTool::class,

        // Journals
        ListJournalsTool::class,
        GetJournalTool::class,
        CreateJournalTool::class,
        UpdateJournalTool::class,
        DeleteJournalTool::class,

        // Journal Folders
        ListJournalFoldersTool::class,
        GetJournalFolderTool::class,
        CreateJournalFolderTool::class,
        UpdateJournalFolderTool::class,
        DeleteJournalFolderTool::class,

        // Links
        ListLinksTool::class,
        CreateLinkTool::class,
        UpdateLinkTool::class,
        DeleteLinkTool::class,
        BulkLinkMaintenanceTool::class,

        // Images
        GetImageUsageTool::class,
        GenerateImageTool::class,
        InitImageUploadTool::class,
        CompleteImageUploadTool::class,
        DeleteEntityImageTool::class,
    ];

    public static function fake(): self
    {
        return new ArchivistServer(new FakeTransporter);
    }
}
