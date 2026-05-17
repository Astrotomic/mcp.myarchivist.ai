# Archivist AI MCP Server

The official [Model Context Protocol](https://modelcontextprotocol.io) (MCP) server for [Archivist AI](https://www.myarchivist.ai) -- a TTRPG campaign memory platform for game masters and players.

Connect AI assistants like Claude, ChatGPT, Cursor, and Windsurf directly to your campaign data: characters, sessions, locations, factions, items, quests, journals, and more.

## Quick Start

**MCP Server URL:** `https://mcp.myarchivist.ai/mcp`

### Claude Desktop

Add to your `claude_desktop_config.json`:

```json
{
  "mcpServers": {
    "archivist-ai": {
      "type": "streamable-http",
      "url": "https://mcp.myarchivist.ai/mcp"
    }
  }
}
```

### Cursor

Add to `.cursor/mcp.json` in your project:

```json
{
  "mcpServers": {
    "archivist-ai": {
      "type": "streamable-http",
      "url": "https://mcp.myarchivist.ai/mcp"
    }
  }
}
```

### Windsurf

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

### ChatGPT

Archivist AI is available as a ChatGPT plugin. Search for "Archivist AI" in the ChatGPT plugin store.

## Authentication

The MCP server supports two authentication methods:

- **OAuth 2.0** (recommended for interactive clients): Authorization code flow with PKCE. When you first use a tool, your MCP client will redirect you to sign in with your Archivist account.
- **Bearer token**: Pass your Archivist API key as a Bearer token for programmatic access.

Get your API key from the [Developer tab](https://app.myarchivist.ai/profile?section=dev) in your Archivist profile.

### OAuth Details

- Authorization Server Metadata: `https://mcp.myarchivist.ai/.well-known/oauth-authorization-server`
- Supported scopes: `profile`, `worlds_read`, `sessions_read`, `characters_read`

## Server Details

| Property | Value |
|----------|-------|
| **Name** | Archivist AI |
| **Version** | 1.0.0 |
| **Transport** | Streamable HTTP |
| **URL** | `https://mcp.myarchivist.ai/mcp` |
| **Tools** | 25 read-only tools |
| **Resources** | None (v1) |
| **Prompts** | None (v1) |

## Available Tools

All tools are **read-only**, **non-destructive**, and **idempotent**. Write operations are planned for v2.

### Campaigns

| Tool | Description |
|------|-------------|
| `list_campaigns` | List your campaigns. Returns a paginated list. |
| `get_campaign` | Get a specific campaign by ID. |
| `get_campaign_stats` | Get statistics for a campaign: character count, session count, and more. |

### Characters

| Tool | Description |
|------|-------------|
| `list_characters` | List characters in a campaign. Filter by name, type (PC/NPC), or approval status. |
| `get_character` | Get a character by ID including aliases, backstory, and speaker linkage. |

### Sessions

| Tool | Description |
|------|-------------|
| `list_sessions` | List game sessions. Filter by session type or public-only. |
| `get_session` | Get a session by ID. Optionally include related beats and moments. |
| `get_session_cast_analysis` | Get cast analysis: talk-share breakdown and core session metrics. |

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

## Discovery

- **Server Card:** `https://mcp.myarchivist.ai/.well-known/mcp/server-card.json`
- **MCP Discovery:** `https://mcp.myarchivist.ai/.well-known/mcp`
- **OAuth Metadata:** `https://mcp.myarchivist.ai/.well-known/oauth-authorization-server`

## Available On

- [Smithery](https://smithery.ai/servers/me-26lt/archivist-ai) -- MCP server registry
- [Glama](https://glama.ai/mcp/connectors/ai.myarchivist.mcp/mcp-archivist-ai) -- MCP connector
- [mcp.so](https://mcp.so/server/archivist-ai/Archivist%20AI) -- MCP server directory

## Resources

- [Archivist AI](https://www.myarchivist.ai) -- Product homepage
- [Developer Portal](https://developers.myarchivist.ai) -- API reference, playground, guides
- [Agent Examples](https://github.com/Archivist-AI/agent-examples) -- Working agent examples and configs
- [REST API](https://api.myarchivist.ai) -- Direct API access
- [OpenAPI Spec](https://api.myarchivist.ai/openapi.json) -- Machine-readable API specification
- [Discord](https://discord.gg/t3yk6AWyg7) -- Community and support

## Technology

Built with [Laravel](https://laravel.com) and [laravel/mcp](https://github.com/laravel/mcp). The server proxies authenticated requests to the Archivist REST API (`https://api.myarchivist.ai`).

## License

MIT
