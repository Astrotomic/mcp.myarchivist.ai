# Archivist AI plugin for Claude

Connects Claude to [Archivist AI](https://www.myarchivist.ai), the campaign memory platform for tabletop RPG game masters and players.

Once installed, Claude can read and write your campaign data: campaigns, characters, sessions, beats, key moments, hero awards, quotes, spotlights, factions, locations, items, quests, journals, and the links between them.

## How it connects

The plugin adds one remote MCP server:

- **URL:** `https://mcp.myarchivist.ai/mcp` (streamable HTTP)
- **Sign-in:** OAuth. The first time Claude uses a tool, you're asked to sign in to Archivist AI in your browser and approve access. The plugin itself stores no credentials and reads nothing from your machine.

Write tools (create, update, delete, image upload) need the `agent_write` scope. If they're missing, reconnect and approve write access.

## Included skill

- **`archivist-get-started`**: first-run onboarding. When someone has just connected Archivist or their campaign is empty, Claude asks what they have, such as a recap, notes, a transcript, a character sheet or just what they remember. Claude then previews a draft and, with their confirmation, saves it as their first session, a journal entry or compendium records. A session uses one of the account's sessions and always goes to Session Review in the app before anything is added to the campaign.

## Links

- Product: https://www.myarchivist.ai
- MCP docs: https://www.myarchivist.ai/mcp
- Privacy policy: https://www.myarchivist.ai/privacy
- Support: support@myarchivist.ai
- Source: https://github.com/Astrotomic/mcp.myarchivist.ai
