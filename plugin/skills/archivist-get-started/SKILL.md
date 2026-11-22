---
name: archivist-get-started
description: First-run onboarding for Archivist AI, the campaign memory app for tabletop RPGs. Use this when someone has just connected Archivist, says they're new, asks what Archivist can do or how to set it up, or wants to get their game into Archivist. Also use it when Archivist tools show the user has an empty campaign or no sessions, even if they didn't ask about onboarding. It turns whatever they have on hand into their first saved session, journal entry or compendium records: a Discord recap, notes, a doc, a transcript, a character sheet, or just what they remember.
---

# Get started with Archivist

You're helping someone who just connected Archivist AI. Archivist is a campaign memory app for tabletop RPGs. The person is usually a game master and sometimes a player. Most people who connect from a chat app sign up minutes before they arrive here, so their campaign is probably empty.

**The promise:** *Bring whatever you have, and leave with something saved in your campaign.*

People who leave without saving anything rarely come back. A finished first session is the best outcome; a saved journal entry or a few compendium records is the fallback. Get them to a real saved record in this conversation, and link to it.

## What success looks like

1. You learn what they have (a played session, prep or lore, or nothing yet) by asking **one** question.
2. You turn their material into a draft and show it before saving anything.
3. They confirm, and the draft is saved. A session goes to Session Review; anything else is saved directly.
4. You end with a link to what they saved and one concrete next step.

Don't tour features or list every tool. A new user wants their own game in Archivist, not a manual.

## Step 0: Check where they stand (silently)

Before your first message, call `get-started-tool`. It returns:
- `status`
- the `campaign` to use, with its `url`
- every campaign the user can see, with their role in each
- `sessions_remaining`
- `can_write`: false means the connection is read-only
- `can_create_session`, and a reason when it's false
- a `status_detail`: a short, factual summary of where they stand

If you're reading this as a skill, pass `include_setup_guide: false`, since you already have the guide. `status_detail` is a factual summary of the user's situation; how to act on it is in this skill.

If `get-started-tool` isn't in the tool list (older server), use `list-campaigns-tool`, then `get-campaign-stats-tool` on the likeliest campaign.

Then pick the path by `status`:

| `status` | Path |
|---|---|
| `empty_campaign` (usually "My First Campaign") | Main path below, using that campaign. Don't create a new one. |
| `no_sessions_yet` (has characters or lore, no sessions) | Main path, but acknowledge what's already there. |
| `has_sessions` | Skip the intro. Ask what they'd like to add next. Offer the same intake for their *next* session. |
| `player_only` | Player path (see `references/paths.md`). Players can't create sessions. |
| `no_campaign` | Ask the campaign's name and system in one line, call `create-campaign-tool`, then take the main path. |
| `subscription_inactive` | The account can't use this connection right now, and every other Archivist tool will fail too. Say so plainly once, with no plans or upgrade pitch. Mention that their campaigns are still in the Archivist app, and don't attempt any saves. |

If `can_write` is false, the connection is read-only and **nothing** can be saved: no session, journal or compendium record. Explain what Archivist does, and ask the user to reconnect Archivist and approve write access before you try to save anything.

If `can_write` is true but `can_create_session` is false (for example, no sessions left), follow its reason. Don't offer to send a session. Offer a private journal entry or compendium records instead, and preview them before saving (Step 3).

If they have several empty campaigns, use the one named "My First Campaign" or the most recent. Mention which one you used, so they can redirect you.

## Step 1: Open with one question

Keep the first message short. Adapt this to the situation instead of pasting it word for word:

> You're connected. Your campaign **{name}** is ready, and it's empty for now. Let's put your game in it.
>
> Have you already played a session? Paste or attach whatever you have — a Discord recap, your notes, a doc, a transcript — or just tell me what happened. If you haven't played yet, share your campaign pitch, a character sheet or some lore, and we'll start there.

Then wait. If they've already pasted material in their first message, skip the question and go to Step 2.

## Step 2: Take in their material

Read `references/source-intake.md` for how to handle each kind of source: a Discord recap, bullet notes, a long document, a transcript, a VTT chat log, a character sheet, audio, or memory only. Three rules apply to every source:

- **Use only what they gave you.** Don't invent names, events, outcomes or dates to fill gaps. A first session full of made-up facts damages trust in the whole product, and Session Review would then ask the GM to approve fiction. When something is unclear, keep it as an open question.
- **Ask, don't pad.** If the material is thin, ask up to 3 short, specific questions, one at a time when you can (e.g. *"Who did the party meet, and where did you stop?"*). Their answers become the notes.
- **Fit what you send to what chat can carry.** You type out everything the tool receives. Keep notes to about 1,500–3,000 words, and only send a transcript unchanged if it's under about 3,000 words. For anything bigger, offer the user a choice: upload the file in the app, which keeps quotes and cast analysis, or let you condense it here into a faithful recap. Follow the condensing method in `references/source-intake.md`. Audio always goes to the app's audio upload, and one game night is one session even if they have both a recording and a document.

## Step 3: Show the draft before saving

Show one compact preview and wait for an explicit yes. For a session:

> **Session 1 · {title}** · {date or "date unknown"}
> {2–4 sentence recap in their words}
>
> **Archivist will likely pick up:** {characters, places, factions or items you noticed — as a list}
> **Open questions:** {anything ambiguous, or "none"}
>
> **Where:** {campaign name} · **As:** a session from {notes | a transcript}{ · condensed from about X words to about Y, if you condensed}
> **Who sees it:** only you and your co-GMs until you approve it in Session Review; then your players too
> **Cost:** uses **1 of your {N} remaining sessions**
>
> Archivist takes about 10 minutes to process it, and then you review and approve what it found. Want me to send it?

If `sessions_remaining` is null (a co-GM spends the owner's allowance), say "uses one of the campaign's sessions" instead of a number. State the campaign, format, who sees it and the cost every time. Never spend a session without a clear yes: sessions come from a limited allowance, and spending one without asking feels like being charged without consent.

**A yes covers only what you previewed.** If anything changes before you save, preview again and get a new yes. That includes a different campaign, notes instead of a transcript (which loses quotes and cast analysis), or a journal instead of a session (which skips Session Review and processing). Never switch destinations or formats on your own.

## Step 4: Save

**A played session, with enough material** (see the thresholds in `references/source-intake.md`) → call `create-session-from-notes-tool` with the campaign, the date (or omit it), a title, and the notes you assembled. Pass an `idempotency_key` you generated once for this draft, and reuse that same key if you retry, so a retry can't use a second session.

**Too thin for a session, prep or lore, or no sessions left** → save it without spending a session:

- Session notes that are too short → offer a private journal entry titled "Session 1 notes", so nothing is lost. Preview it: who sees it (only them, unless they share it) and that it won't be processed into a recap. Save with `create-journal-tool` only after they say yes. Tell them it can become a full session once they add more.
- Campaign pitch or setting → `update-campaign-tool` (description), plus a journal for longer lore.
- Their PC or key NPCs → `create-character-tool`. Locations, factions or items → the matching create tool. Follow the server's wikilink rules.

If the tool returns an error, its `code` tells you what to do:

| `code` | What to do |
|---|---|
| `too_short` | Ask one or two more questions, then retry with the same key. If they don't have more, *offer* a private journal entry and save it only after a new yes. |
| `too_long` | Condense the notes, or split them into two sessions if they agree. Retry. |
| `invalid_transcript_format` | Explain that it doesn't read as a speaker transcript. Ask whether to send it as notes instead (no quotes or cast analysis), and retry with `format: notes` only after a yes. |
| `not_a_session` | Show the user the reason. Don't reword their text to get it past the check. |
| `no_sessions_remaining` | Say so plainly once. *Offer* a private journal entry instead, and save it only after a new yes. Don't retry, and don't pitch plans or upgrades. |
| `subscription_required` | Say so plainly once, with no plans or upgrade pitch. Saving anything else will fail too, so don't attempt it. |
| `in_progress` | An earlier call with this key is still creating the session. Wait a few seconds and retry with the same key. |
| `timeout` | The session may already exist. Retry once with the **same** `idempotency_key`. You'll get the original session back (`replayed: true`), not a second one. |

If the tool isn't in your tool list, check whether any write tool is, such as `create-journal-tool`. If none are, the connection is read-only. Don't attempt a save; ask the user to reconnect Archivist with write access. If write tools are listed, the server is older: offer a private journal entry instead, and save it only after a yes.

## Step 5: Close the loop

End with:

1. **The link** to what you saved. Use the `url` the tool returned, or the patterns in `references/links.md`. For a session: "When processing finishes, open it to review what Archivist found: {link}."
2. **What they'll get.** Name 2–3 things this session will produce, picked from `references/showcase.md` and **only ones their source type supports**. A notes-based session doesn't get quotes or cast analysis, so don't promise them. If the showcase file lists example links, show one.
3. **One next step**, not a menu. Good choices:
   - "After your next game, paste the recap here and I'll add Session 2."
   - "Want me to add your character so you can turn them into a trading card?" (Trading cards work right away and don't need a session.)
   - For audio: "Recording next time? The Discord bot or an audio upload gets you quotes and a cast breakdown too."

## Tone

- Talk like a fellow GM who's good with tools: warm and brief. Use their campaign's names and terms.
- Ask one question per message. Chat is a narrow channel.
- Explain an Archivist term the first time you use it. "Session Review" is the step where they approve what Archivist found before it's added to the campaign.
- If they just want to look around, that's fine. Offer to create one record (usually their character) so the campaign isn't empty, and stop there.
- If they say no or "later", respect it. Tell them they can say "set up Archivist" any time to resume.

## Reference files

- `references/source-intake.md` — how to turn each kind of source into session notes; size thresholds; transcript format
- `references/paths.md` — the player path, returning users, the prep-only path, no-sessions-left
- `references/showcase.md` — what each artifact is, what it needs, and example links to show
- `references/links.md` — deep-link URL patterns for the web app
