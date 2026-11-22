# Turning a source into session notes

`create-session-from-notes-tool` takes **text that you write into the tool call**. Whatever the user gives you (pasted text, an attached document, a transcript), you read it and then type out what gets sent. That shapes everything below. Notes-sized text is easy to send. Very long text is slow to reproduce, and it tends to get cut off or quietly summarized on the way.

## Size limits

The server counts tokens; the word counts here are approximate.

| `format` | When to use it | Server accepts | Send through chat | What processing produces |
|---|---|---|---|---|
| `notes` | Prose or bullet notes, recaps, summaries | About **500–3,300 words** | Aim for **1,500–3,000 words** | Summary, timeline beats, key moments, suggested compendium records, hero awards. **No quotes, no cast analysis.** |
| `transcript` | `Speaker: words` dialogue (at least 70% of lines) | About **875–66,000 words** | **Only up to about 3,000 words**, passed unchanged | Everything `notes` produces, plus memorable quotes and (for owners) cast analysis |

If the tool returns `too_short` or `too_long`, its `details` has the exact limits and your token count. Trust those over these numbers.

The server also checks that the text describes a tabletop session. If it rejects the text, show the user its reason. Don't reword the text to get it through.

## Too much text for chat

Use this when the material is a transcript over about 3,000 words, or notes over about 3,300 words. Give the user the choice, and recommend the first option for transcripts:

1. **Upload the file in the app (keeps everything).** Link the session form's text upload (`links.md`). The app reads the file itself, with no size problem, keeps the exact wording, and still produces quotes and cast analysis. Offer to save anything else they shared (prep, lore) here while they do that.
2. **Condense it here into notes.** You write a faithful recap of up to about 3,000 words and send it as `format: notes`. The trade-off: a condensed transcript loses quotes and cast analysis, because those need the original wording. Say so, and get a yes before condensing. This changes the format, so it needs fresh consent (SKILL.md Step 3).

Never send a long transcript as `format: transcript` after shortening it. A trimmed or paraphrased transcript is no longer a transcript. If you shorten anything, it's notes.

### How to condense faithfully

The goal is a recap of the session as it was played. It is not a summary of the summary.

1. **Read all of it first.** If the host cut off the attachment, or you can't see the whole document, say so. Don't condense what you couldn't read. Ask them to paste the rest in parts, or upload the file in the app.
2. **Split by session.** If the text covers more than one game night, ask which one to turn into a session (usually the most recent or the first). The answer selects a source. Preview that night alone and wait for a later reply approving the save. Each session uses one from the allowance. The rest can go into a private journal entry after a separate preview and approval.
3. **Keep, in order of play:**
   - Where the party was and why, at the start
   - Every named character, creature, place, faction and item, spelled exactly as in the source
   - What each player character did that mattered: decisions, reveals, deals, betrayals
   - How fights and challenges ended, plus deaths and injuries, loot and rewards
   - New quests, promises and open threads, and where they stopped
4. **Cut:** table talk, rules lookups, dice math, breaks, out-of-character chatter, recaps of earlier sessions, and repeated descriptions.
5. **Write it as a recap:** past tense, plain sentences, one scene after another. Say who did what. Add no interpretation, motives or connecting detail that isn't in the source. When the source is unclear, say so ("unclear whether…") rather than choosing a version.
6. **Hit the budget.** Aim for 1,500–3,000 words for a full session, and estimate the length before you send it. If it's over, trim the least important scenes rather than cutting off the end. The ending is usually where the hooks for next session are.
7. **Say what you did in the preview:** "Condensed from about 9,000 words to about 2,400." If the source was a transcript, add "sent as notes: no quotes or cast analysis".

## Audio recordings

Audio can't go through chat. These hosts can't turn a session recording into a transcript, and nothing here can upload the file to Archivist. Link the session form's audio upload (`links.md`). Audio is also the richest source: it's the one that produces quotes and cast analysis. Don't ask them to transcribe it themselves to fit chat.

## Audio and a document for the same game night

**One game night is one session.** Making a session from the doc *and* sending them to upload the audio creates two sessions for the same night, and uses two from their allowance.

- Recommend the audio upload in the app as the session.
- Offer to use the document alongside it, so it isn't wasted:
  - as a private journal entry (GM notes, prep)
  - for compendium records (NPCs, places) if it describes them
- If they'd rather not upload the audio, make the session from the document alone, using the rules above.
- If they say “do both,” explain that two sessions use two from their allowance and check that there is room for both. Preview which session they'll upload in the app and which you'd create from notes. Ask for a separate yes to that plan before calling any write tool. If only one session is available, ask them to choose which source to use.

## By source

**Discord recap / bullet notes / "here's what happened"**
- Use `format: notes`.
- Keep their wording. Turn fragments into short sentences, in the order events happened. Keep names exactly as they spell them.
- Under about 500 words: ask up to 3 follow-up questions. Each answer should add real content. Useful ones:
  - "How did the session open: where was the party and why?"
  - "Who did they meet or fight? Any names?"
  - "What was the big moment or decision?"
  - "Where did you stop, and what's hanging for next time?"
- Still too short after that: offer a private journal entry. “That's all I remember” ends intake. Show the journal preview and get an explicit yes to that save before calling the journal tool (SKILL.md Step 3). Never pad with invented detail or submit a session to test the limit.

**A long document (campaign notes, a GM's prep-and-play log)**
- Ask whether it covers one session or several, then follow **How to condense faithfully** above if it's over budget.

**A transcript (from Zoom, Otter, Craig, a VTT export)**
- Up to about 3,000 words with most lines in `Name: words` form: use `format: transcript` and pass it **unchanged**. Quotes and cast analysis depend on the original wording.
- Longer than that: follow **Too much text for chat** above.
- Timestamps or speaker IDs like `SPEAKER_01`: ask once who's who, then substitute the names. Change nothing else.
- Not dialogue (a summary someone wrote): use `format: notes`.

**Play-by-post or Discord text log**
- If it reads as `Name: message` lines, treat it as a transcript; long logs follow **Too much text for chat**.
- Otherwise use it as notes.
- Mention that the Discord bot can import play-by-post channels directly in the web app next time.

**Character sheet, campaign pitch, world lore (nothing played yet)**
- This isn't a session. Take the prep path in `paths.md`.

**"I don't have anything, I just remember it"**
- Interview them. Ask 3–5 questions, one per message, from the list above.
- Then write the notes from their answers in a clean recap voice, and show the draft (Step 3).
- Sessions from memory are often short. Say early, and without making it a big deal, that if it ends up thin you'll offer to save it as a private journal entry for now.

## Title and date

- **Title:** use theirs if they have one. Otherwise suggest a short, evocative one from the main event ("Trouble at the Ferry") and let them change it.
- **Date:** ask only if it isn't in the material. "Not sure" is fine; leave the date out and the server uses today's.
