# Archivist AI MCP Server

[![MIT License](https://img.shields.io/github/license/Astrotomic/mcp.myarchivist.ai.svg?label=License&color=blue&style=for-the-badge)](https://github.com/Astrotomic/mcp.myarchivist.ai/blob/master/LICENSE)
[![Treeware](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-green?style=for-the-badge)](https://plant.treeware.earth/Astrotomic/mcp.myarchivist.ai)
[![Larabelles](https://img.shields.io/badge/Larabelles-%F0%9F%A6%84-lightpink?style=for-the-badge)](https://www.larabelles.com/)

[![GitHub PHPunit Status](https://img.shields.io/github/actions/workflow/status/Astrotomic/mcp.myarchivist.ai/phpunit.yml?style=flat-square&logoColor=white&logo=github&label=PHPunit)](https://github.com/Astrotomic/mcp.myarchivist.ai/actions/workflows/phpunit.yml)
[![GitHub PHPStan Status](https://img.shields.io/github/actions/workflow/status/Astrotomic/mcp.myarchivist.ai/phpstan.yml?style=flat-square&logoColor=white&logo=github&label=PHPStan)](https://github.com/Astrotomic/mcp.myarchivist.ai/actions/workflows/phpstan.yml)
[![GitHub Pint Status](https://img.shields.io/github/actions/workflow/status/Astrotomic/mcp.myarchivist.ai/pint.yml?style=flat-square&logoColor=white&logo=github&label=Pint)](https://github.com/Astrotomic/mcp.myarchivist.ai/actions/workflows/pint.yml)
[![GitHub PHPMND Status](https://img.shields.io/github/actions/workflow/status/Astrotomic/mcp.myarchivist.ai/phpmnd.yml?style=flat-square&logoColor=white&logo=github&label=PHPMND)](https://github.com/Astrotomic/mcp.myarchivist.ai/actions/workflows/phpmnd.yml)

[![Discord](https://img.shields.io/badge/Discord-MyArchivist-5865F2?style=flat-square&logoColor=white&logo=discord)](https://discord.gg/t3yk6AWyg7)
[![Smithery](https://img.shields.io/badge/Smithery-MyArchivist-ff5601?style=flat-square)](https://smithery.ai/servers/me-26lt/archivist-ai)
[![Glama](https://img.shields.io/badge/Glama-MyArchivist-00d992?style=flat-square)](https://glama.ai/mcp/connectors/ai.myarchivist.mcp/mcp-archivist-ai)
[![mcp.so](https://img.shields.io/badge/mcp.so-MyArchivist-c96442?style=flat-square)](https://mcp.so/server/archivist-ai/Archivist%20AI)

The official [Model Context Protocol](https://modelcontextprotocol.io) (MCP) server for [Archivist AI](https://www.myarchivist.ai) -- a TTRPG campaign memory platform for game masters and players.

Registry metadata lives in [`server.json`](./server.json). Publishing to the [official MCP Registry](https://modelcontextprotocol.io/registry) is automated on version tags via [`.github/workflows/publish-mcp.yml`](./.github/workflows/publish-mcp.yml) (`git tag v1.0.0 && git push origin v1.0.0`).

Connect AI assistants like Claude, ChatGPT, Cursor, Notion, and Windsurf directly to your campaign data: characters, sessions, locations, factions, items, quests, journals, and more.

## Quick Start

**MCP Server URL:** `https://mcp.myarchivist.ai/mcp`

<details>
<summary>Claude Desktop</summary>

Claude Desktop requires the [`mcp-remote`](https://github.com/geelen/mcp-remote) proxy (Node.js must be installed).

Add to your `claude_desktop_config.json` (`~/Library/Application Support/Claude/claude_desktop_config.json` on macOS, `%APPDATA%\Claude\claude_desktop_config.json` on Windows):
```json
{
  "mcpServers": {
    "archivist-ai": {
      "command": "npx",
      "args": [
        "-y",
        "mcp-remote",
        "https://mcp.myarchivist.ai/mcp",
        "--header",
        "Authorization:${AUTH_HEADER}"
      ],
      "env": {
        "AUTH_HEADER": "Bearer YOUR_API_KEY"
      }
    }
  }
}
```

Replace `YOUR_API_KEY` with your Archivist AI API key from [app.myarchivist.ai](https://app.myarchivist.ai). Restart Claude Desktop after saving.
</details>

<details>
<summary>Cursor</summary>

Add to `.cursor/mcp.json` in your project:
```json
{
  "mcpServers": {
    "archivist-ai": {
      "url": "https://mcp.myarchivist.ai/mcp"
    }
  }
}
```
</details>

<details>
<summary>Windsurf</summary>

Add to your MCP configuration:
```json
{
  "mcpServers": {
    "archivist-ai": {
      "serverUrl": "https://mcp.myarchivist.ai/mcp"
    }
  }
}
```
</details>

<details>
<summary>ChatGPT</summary>

Archivist AI is available as a ChatGPT plugin. Search for "Archivist AI" in the ChatGPT plugin store.
</details>

## Available Tools

Read tools are non-destructive and idempotent. Write tools follow standard REST semantics — deletes are destructive and idempotent; PATCH is non-idempotent; PUT is idempotent. Every read tool that returns text-carrying descriptions (Character, Faction, Location, Item, Moment, Beat, Session, Journal) accepts an optional `with_links` parameter — see [Wikilinks](#wikilinks) below for why you almost always want to pass `with_links: true` before editing.

Campaign delete, session delete, product-view-only endpoints (beat reorder/batch-edit, campaign settings, cast/member management), and multipart recording uploads are intentionally not exposed.

### OAuth scopes and write access

OAuth clients that connect via `https://app.myarchivist.ai` request scopes at authorization time. Every write tool (create/update/delete + image generation and upload) additionally requires the `agent_write` scope on the caller's token.

The MCP mirrors the API's enforcement in `tools/list`: agent clients without `agent_write` see only the read tools, and receive a "tool not found" JSON-RPC error if they attempt to call a write tool anyway. API-key credentials and non-agent OAuth clients bypass this filter — the API is the authoritative gate for them and all tools remain visible.

Existing OAuth clients that connected before `agent_write` was advertised need to re-authorize to receive a token with the new scope.

### Campaigns

| Tool | Description |
|------|-------------|
| `list_campaigns` | List your campaigns. Returns a paginated list. |
| `get_campaign` | Get a specific campaign by ID. |
| `get_campaign_stats` | Get statistics for a campaign: character count, session count, and more. |
| `create_campaign` | Create a new campaign. Subject to the account's subscription-tier campaign limit. |
| `update_campaign` | Partially update campaign metadata (title, description, tones, public/mature flags). |

### Sessions

| Tool | Description |
|------|-------------|
| `list_sessions` | List game sessions. Filter by session type or public-only. |
| `get_session` | Get a session by ID. Optionally include related beats and moments. |
| `get_session_cast_analysis` | Get cast analysis: talk-share breakdown and core session metrics. |
| `get_session_transcript` | Get the cleaned transcript for a game session, including utterances, full text, and aggregate stats. |
| `get_session_handout` | Get the generated session handout for a game session, including summary, outlines, spotlights, and notable moments. |
| `patch_session` | Partial update (title, session_date, summary, image). Explicit-link contract for wikilinks. |
| `update_session` | Full update (PUT). Explicit-link contract for wikilinks. |

### Story Structure

| Tool | Description |
|------|-------------|
| `list_beats` | List beats ordered by index. Beats represent story moments (major, minor, step). |
| `get_beat` | Get a specific beat by ID. |
| `create_beat` | Create a beat. Explicit-link contract for wikilinks. |
| `update_beat` | Partially update a beat. |
| `delete_beat` | Delete a beat (child beats have parent_id cleared). |
| `list_moments` | List moments in a campaign or session. Moments capture memorable quotes and events. |
| `get_moment` | Get a specific moment by ID. |
| `create_moment` | Create a moment attached to a session. Explicit-link contract for wikilinks. |
| `update_moment` | Partially update a moment. |
| `delete_moment` | Delete a moment. |

### World Building

| Tool | Description |
|------|-------------|
| `list_characters` | List characters in a campaign. Filter by name, type (PC/NPC), or approval status. |
| `get_character` | Get a character by ID including aliases, backstory, and speaker linkage. |
| `create_character` | Create a character. Description/backstory wikilinks auto-resolve. |
| `update_character` | Partially update a character. Read with `with_links: true` before editing description/backstory. |
| `delete_character` | Delete a character. API unbrackets all inbound `[[alias]]` references automatically. |
| `list_factions` | List factions. Factions represent guilds, organisations, or other groups. |
| `get_faction` | Get a specific faction by ID. |
| `create_faction` | Create a faction. Description wikilinks auto-resolve. |
| `update_faction` | Partially update a faction. Read with `with_links: true` before editing. |
| `delete_faction` | Delete a faction. |
| `list_locations` | List locations. Locations can be nested (cities, taverns, dungeons, etc.). |
| `get_location` | Get a specific location by ID. |
| `create_location` | Create a location. Description wikilinks auto-resolve. |
| `update_location` | Partially update a location. Read with `with_links: true` before editing. |
| `delete_location` | Delete a location (child locations have parent_id cleared). |
| `list_items` | List items. Items include weapons, armour, artefacts, and other notable objects. |
| `get_item` | Get a specific item by ID. |
| `create_item` | Create an item. Description wikilinks auto-resolve. |
| `update_item` | Partially update an item. Read with `with_links: true` before editing. |
| `delete_item` | Delete an item. |

### Quests

| Tool | Description |
|------|-------------|
| `list_quests` | List quests with pagination. Filter by status or category. |
| `get_quest` | Get a fully expanded quest: objectives, progress log, related entities, session provenance. |
| `create_quest` | Create a quest entry. Quests do not participate in the wikilinks system. |
| `update_quest` | Partially update a quest. Lists you send replace their corresponding lists on the record. |
| `delete_quest` | Delete a quest and its objectives / progress entries / related refs. |

### Journals

| Tool | Description |
|------|-------------|
| `list_journals` | List journal entries. Content omitted from list; use get_journal for full content. |
| `get_journal` | Get a journal entry by ID including full content and permission level. |
| `create_journal` | Create a journal entry. Returns `{success, id}`. |
| `update_journal` | Update a journal entry (PUT). Returns `{success, id}`. |
| `delete_journal` | Delete a journal entry (embeddings are best-effort deleted). |
| `list_journal_folders` | List journal folders ordered by path and position for tree rendering. |
| `get_journal_folder` | Get a specific journal folder by ID. |
| `create_journal_folder` | Create a journal folder (owners/admins only; path must be unique per campaign). |
| `update_journal_folder` | Update a journal folder (PUT). |
| `delete_journal_folder` | Delete a folder; entries move to the campaign root. |

### Relationships

| Tool | Description |
|------|-------------|
| `list_links` | List links between entities. Filter by source/target entity and relationship alias. |
| `create_link` | Create a Link row between two entities (upserts if `(from_type, from_id, alias)` collides). |
| `update_link` | Update an existing link's alias. |
| `delete_link` | Delete a single Link row (does not rewrite the source's text). |
| `bulk_link_maintenance` | Trigger campaign-wide link maintenance (add/remove/update) via webhook — requires an active subscription tier. |

### Images

| Tool | Description |
|------|-------------|
| `get_image_usage` | Return the calling account's image quota for a campaign: `used`/`limit`, `tier`, `can_access`, and cycle window. Call before `generate_image` to avoid quota errors. |
| `generate_image` | Server-side AI generation for `character`, `faction`, `location`, `item`, or `world`. Rewrites the entity's description into an image prompt (`user_input` optional). Returns a public URL — set it on the entity via the matching update tool if you want to attach it. Consumes the account's image quota. |
| `init_image_upload` | Step 1 of direct upload: reserve an `object_key` and receive a presigned S3 PUT URL. The client then PUTs the raw image bytes to `upload_url` with the same `Content-Type`. Expires after `expires_in_seconds`. |
| `complete_image_upload` | Step 2 of direct upload: validate the uploaded object, run NSFW moderation, and (when `attach: true`) set the entity's `image` field to the moderated URL. |
| `delete_entity_image` | Remove an image. Provide `entity_type` + `entity_id` (detaches AND deletes the object) or `image_url` (deletes just the object). |

## Wikilinks

Descriptions, summaries, moment content, and journal bodies in Archivist AI can contain `[[Target Name|Optional Alias]]` markup that resolves to real records. Every write tool that touches a text field carrying wikilinks has to follow a small protocol to avoid destroying existing links.

### The one rule you must follow

**Always read with `with_links: true` before writing.** Every Get/List tool for Character, Faction, Location, Item, Moment, Beat, Session, and Journal accepts this parameter. The API defaults to stripping wikilinks so that read consumers get clean prose, which means a naive read/modify/write cycle will erase every `[[…]]` on the source record. Passing `with_links: true` returns the text with brackets intact so you can preserve them on write.

### Per-record contract

| Record type | Contract |
|-------------|----------|
| Character, Faction, Location, Item, Character.backstory | **Auto-resolve.** On write, the API extracts `[[…]]` from your new text, matches each against records in the same campaign, and syncs the Link table (inserts new, updates changed, deletes removed). New `[[Alias]]` markup for a matchable target creates a Link automatically. |
| GameSession, Beat, Moment | **Explicit-link only.** The API strips `[[Alias]]` markup that does NOT already have a matching Link row for that source. To add a wikilink, first call `create_link` with the correct `from_type`/`from_id`, then include `[[Alias]]` in the text on your write. |
| Journal | **Rendering-only.** Stored `[[…]]` markup renders as links on read with `with_links: true` but writes do not auto-populate the Link table. Use `create_link` (`from_type=Journal`) if you want persistent link tracking. |
| Quest | **No wikilinks.** Quest text fields are stored verbatim. Relationships are modelled via the `related_*` list fields, not wikilinks. |
| Delete (any record) | The API automatically unbrackets all `[[alias]]` references to a deleted record across every referring text field. No manual cleanup is required. |

### Renaming and repointing

- **Rename an alias**: edit inside the brackets. `[[Alice|Al]]` → `[[Alice|Ali]]`.
- **Repoint at a different target**: change the visible target name. `[[Alice]]` → `[[Alicia]]`.
- **Remove a link**: unbracket the alias in the text (auto-resolve) or call `delete_link` (explicit).
- **Campaign-wide rename or repoint** for a compendium target: use `bulk_link_maintenance` with `operation: "update"`, `new_alias`, and/or `new_target_id`. The API rewrites every referring text field in a background webhook and returns a `task_id`.

## Images

Entity images can be added to Characters, Factions, Locations, Items, Moments, Sessions, and the Campaign itself. Two flows are supported:

### Server-side AI generation

Call `generate_image` with a `campaign_id`, a `type` (`character`, `faction`, `location`, `item`, or `world`), and optionally an `entity_id` (defaults to the campaign for `type=world`) and free-form `user_input`. The API rewrites the entity's stored description into a visual prompt, calls the configured provider, and returns a public URL.

Two things worth calling out:
1. The generated image is **not** automatically attached — set `image` on the entity via the matching update tool (e.g. `update_character`, `update_faction`) if you want to persist it on the record.
2. Generation consumes the account's per-cycle image quota. Call `get_image_usage` first if you need to reason about headroom, tier limits, or the current billing window. AI features are disabled on archived campaigns.

### Direct upload (two-step)

For images the user already has on disk or in memory, use the presigned-URL flow:

1. Call `init_image_upload` with `campaign_id`, `entity_type`, `entity_id`, `file_name`, and `content_type` (must be `image/*`). You receive `object_key`, `upload_url`, `public_url`, and `expires_in_seconds`.
2. Your client (or a human collaborator) issues an HTTP `PUT` to `upload_url` with the raw image bytes and the same `Content-Type` header before the URL expires. This step happens outside the MCP transport.
3. Call `complete_image_upload` with `object_key`, `entity_type`, `entity_id`, and `attach` (defaults to `true`). The API validates the upload, runs NSFW moderation, and — when `attach` is true — sets the entity's `image` field to the moderated `public_url`.

Agents that cannot make arbitrary HTTP PUTs should prefer `generate_image` or hand the PUT step off to a human between step 1 and step 3.

### Removal

`delete_entity_image` removes an image within a campaign in one of two modes:
- **By entity**: pass `entity_type` + `entity_id`. The API detaches the image from the record AND deletes the underlying object.
- **By URL**: pass a managed `image_url` (e.g. from a previous `init_image_upload` response). Deletes just the object.
