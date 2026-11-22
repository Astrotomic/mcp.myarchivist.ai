# Web app links

The base is `https://app.myarchivist.ai`. When a tool result includes a `url` field, use it. Build a URL from these patterns only when there's no `url` field.

| Destination | URL |
|---|---|
| Campaign home | `/campaigns/{campaign_id}` |
| Campaign section | `/campaigns/{campaign_id}?section={section}`. Sections: `recaps`, `timeline`, `characters`, `locations`, `factions`, `items`, `journals`, `hero-awards`, `key-moments`, `memorable-quotes`, `quest-log`, `artifacts` |
| Session, including Session Review once processing finishes | `/gamesessions/{session_id}` |
| Session handout (2-page PDF) | `/gamesessions/{session_id}?handout=true` |
| Cast analysis (owners only, transcript or audio sessions) | `/gamesessions/{session_id}?castAnalysis=1` |
| Compendium record | `/compendium/{characters\|creatures\|locations\|factions\|items}/{id}` |
| Trading card for a record | `/compendium/{type}/{id}?dtc=true` |
| Upload audio for a new session | `/campaigns/{campaign_id}?intent=audio_upload&newSession=true&onboardingBypass=logSession` |
| Upload a text file or long transcript for a new session | `/campaigns/{campaign_id}?intent=text_upload&newSession=true&onboardingBypass=logSession` |
| New session form (all options, including Discord) | `/campaigns/{campaign_id}?newSession=true` |
| Tutorials | `/academy` |

Show links as markdown with a readable label, e.g. `[Review Session 1](https://app.myarchivist.ai/gamesessions/abc123)`.
