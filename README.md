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

All tools are **read-only**, **non-destructive**, and **idempotent**. Write operations are planned for v2.

### Campaigns

| Tool | Description |
|------|-------------|
| `list_campaigns` | List your campaigns. Returns a paginated list. |
| `get_campaign` | Get a specific campaign by ID. |
| `get_campaign_stats` | Get statistics for a campaign: character count, session count, and more. |

### Sessions

| Tool | Description |
|------|-------------|
| `list_sessions` | List game sessions. Filter by session type or public-only. |
| `get_session` | Get a session by ID. Optionally include related beats and moments. |
| `get_session_cast_analysis` | Get cast analysis: talk-share breakdown and core session metrics. |
| `get_session_transcript` | Get the cleaned transcript for a game session, including utterances, full text, and aggregate stats. |
| `get_session_handout` | Get the generated session handout for a game session, including summary, outlines, spotlights, and notable moments. |

### Story Structure

| Tool | Description |
|------|-------------|
| `list_beats` | List beats ordered by index. Beats represent story moments (major, minor, step). |
| `get_beat` | Get a specific beat by ID. |
| `list_moments` | List moments in a campaign or session. Moments capture memorable quotes and events. |
| `get_moment` | Get a specific moment by ID. |

### World Building

| Tool | Description |
|------|-------------|
| `list_characters` | List characters in a campaign. Filter by name, type (PC/NPC), or approval status. |
| `get_character` | Get a character by ID including aliases, backstory, and speaker linkage. |
| `list_factions` | List factions. Factions represent guilds, organisations, or other groups. |
| `get_faction` | Get a specific faction by ID. |
| `list_locations` | List locations. Locations can be nested (cities, taverns, dungeons, etc.). |
| `get_location` | Get a specific location by ID. |
| `list_items` | List items. Items include weapons, armour, artefacts, and other notable objects. |
| `get_item` | Get a specific item by ID. |

### Quests

| Tool | Description |
|------|-------------|
| `list_quests` | List quests with pagination. Filter by status or category. |
| `get_quest` | Get a fully expanded quest: objectives, progress log, related entities, session provenance. |

### Journals

| Tool | Description |
|------|-------------|
| `list_journals` | List journal entries. Content omitted from list; use get_journal for full content. |
| `get_journal` | Get a journal entry by ID including full content and permission level. |
| `list_journal_folders` | List journal folders ordered by path and position for tree rendering. |
| `get_journal_folder` | Get a specific journal folder by ID. |

### Relationships

| Tool | Description |
|------|-------------|
| `list_links` | List links between entities. Filter by source/target entity and relationship alias. |
