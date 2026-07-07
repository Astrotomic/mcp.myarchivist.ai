<?php

namespace App\Mcp;

final class WikilinkPrompts
{
    public const MARKUP_FORMAT = 'Wikilink markup rules: `[[Target Name|Alias]]` uses the left side only to resolve the entity; the right side becomes Link.alias (if omitted, both sides are the same string). The app renders links by matching bracket text to Link.alias — not by guessing from prose typography. Default to `[[Exact Name From API]]` with no pipe, copying character_name or name byte-for-byte from list_* results. Use `[[Target Name|Alias]]` only when the prose uses a shorter or different label than the entity name (e.g. [[Queen Whatsherface|the queen]]). Never write `[[Name|Name]]`, never put the canonical name on the left and a prose variant on the right when both refer to the same entity, and never mix straight/curly apostrophes or other look-alike characters between sides. Re-wrapped aliases from list_links must match the stored alias string exactly inside the brackets.';

    public const UPDATE_COMPENDIUM_TEXT = 'When editing description or backstory, follow this order: (1) fetch the entity with with_links: true; (2) list_links with from_id set to this entity\'s id and from_type matching its compendium type — store every returned alias in memory (writes delete Link rows whose alias no longer appears wrapped in the text); (3) draft the new prose; (4) re-wrap each stored alias as [[alias]] wherever that alias still appears (use [[Target|alias]] only if that link originally used a distinct display alias); (5) for other entity names in the draft, run list_characters/list_factions/list_locations/list_items with search=<candidate name> and read only character_name or name plus aliases from matches — ignore long description fields; (6) wrap confirmed campaign entities using the markup rules below; compendium writes auto-create Link rows. '.self::MARKUP_FORMAT.' Then call this update tool.';

    public const CREATE_COMPENDIUM_TEXT = 'After drafting description (and backstory for characters), before calling create: for each entity name referenced in the text, run list_characters/list_factions/list_locations/list_items with search=<candidate name>, read only character_name or name plus aliases from matches, wrap confirmed records per the markup rules below, then include the linked text in the create payload. Compendium writes auto-create Link rows. '.self::MARKUP_FORMAT;
}
