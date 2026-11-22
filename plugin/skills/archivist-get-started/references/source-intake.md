# Turning a source into session notes

`create-session-from-notes-tool` accepts two kinds of input, and the server enforces size and format limits on both. The sizes below are approximate word counts; the server counts tokens.

| `format` | When to use it | Size the server accepts | What processing produces |
|---|---|---|---|
| `notes` | Prose or bullet notes, recaps, summaries | About **500–3,300 words** | Summary, timeline beats, key moments, suggested compendium records (characters, places, factions, items), hero awards. **No quotes, no cast analysis.** |
| `transcript` | Line-by-line dialogue in `Speaker: text` form | About **875–66,000 words**. At least 70% of lines must look like `Name: words` | Everything `notes` produces, plus memorable quotes and (for owners) cast analysis |

The server also checks that the text describes a tabletop session. If it rejects the text, show its reason to the user. Don't reword the text to get it through.

If the tool returns `too_short` or `too_long`, its `details` has the exact limits and your token count. Trust those over these numbers.

## By source

**Discord recap / bullet notes / "here's what happened"**
- Use `format: notes`.
- Keep their wording. Turn fragments into short sentences in order of events. Keep names exactly as they spell them.
- Under ~500 words: ask up to 3 follow-up questions. Each answer should add real content. Useful ones:
  - "How did the session open: where was the party and why?"
  - "Who did they meet or fight? Any names?"
  - "What was the big moment or decision?"
  - "Where did you stop, and what's hanging for next time?"
- Still too short after that: save a journal instead (see SKILL.md Step 4). Never pad with invented detail to pass the minimum.

**A long doc (campaign notes, a GM's prep-and-play log)**
- Ask whether it covers one session or several.
  - Several: offer to make *this one* Session 1 (usually the first or the most recent; let them pick). Keep the rest as a journal. Each session uses one from their allowance, so don't create several without asking.
- Over ~3,300 words for a single session: condense faithfully. Drop table chatter and rules lookups first, and keep every named person, place and decision.

**A transcript (from Zoom, Otter, Craig, a VTT export)**
- If most lines are `Name: words`, use `format: transcript` and pass it **unchanged**. Quotes and cast analysis depend on the original wording.
- Timestamps or speaker IDs like `SPEAKER_01`: ask who's who once, then substitute the names. Change nothing else.
- Not dialogue (a summary someone wrote): use `format: notes`.

**Play-by-post or Discord text log**
- If it reads as `Name: message` lines, treat it as a transcript.
- Otherwise use it as notes.
- Mention that the Discord bot can import play-by-post channels directly in the web app next time.

**Character sheet, campaign pitch, world lore (nothing played yet)**
- This isn't a session. Take the prep path in `paths.md`.

**"I don't have anything, I just remember it"**
- Interview them. Ask 3–5 questions, one per message, from the list above.
- Then write the notes from their answers in a clean recap voice, and show the draft (Step 3).
- Memory-only sessions are often short. Say early, and without making it a big deal, that if it ends up thin you'll save it as a journal for now.

## Title and date

- **Title:** use theirs if they have one. Otherwise suggest a short, evocative one from the main event ("Trouble at the Ferry") and let them change it.
- **Date:** ask only if it isn't in the material. "Not sure" is fine; omit the date and the server uses today's.

## Uploading files

Some hosts let users attach files. Read the file's text yourself and pass the text. Audio files can't be processed through chat: point them to the audio upload in the web app (`links.md`), which also gets them quotes and cast analysis.
