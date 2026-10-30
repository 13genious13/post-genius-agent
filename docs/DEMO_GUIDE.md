# PostGenius Agent — Demo Guide

**Audience:** Hackathon judges, technical reviewers, and project evaluators  
**Application:** https://pg-agent.itmarketz.com  
**Goal:** Demonstrate a real, traceable AI marketing workflow powered by NVIDIA Nemotron through Nebius Token Factory.

> This is a walkthrough for the supplied final source code. Live account access, completed jobs, and working external integrations must be verified by the project team before the demo. Do not include judge credentials in a public repository.

## 1. What the demo should prove

Judges should be able to see:

1. A real business and marketing goal are stored in the application.
2. The Research Agent uses external evidence and the configured AI provider.
3. The Strategy Agent turns that research into a structured plan.
4. An Agent Run connects campaign context to execution tasks.
5. The Content Agent creates a persisted Content Asset.
6. The Creative Agent generates a visual brief and, if enabled, an image.
7. Human review and publishing safeguards remain visible.
8. Activity and AI usage records demonstrate operational traceability.

## 2. Before recording

- Confirm the demo site loads over HTTPS.
- Create a dedicated demo business with fictional, non-sensitive details.
- Prepare a marketing goal and campaign for that business.
- Complete a Research Run and Strategy beforehand; keep their evidence available.
- Create a completed Agent Run and at least one Content Asset.
- Optionally prepare one completed Creative Asset.
- Verify that the account can access the demo business and all relevant workspaces.
- Run `php artisan phase21:check` and check failed queues privately.
- Keep `PUBLISH_KILL_SWITCH=true` unless a separately approved live publishing test is required.
- Never show API keys, `.env`, private user information, OAuth tokens, or raw server logs.

**Demo example (illustrative, not seeded automatically):** A local café launching a seasonal drinks campaign. Use clearly fictional business details and non-deceptive product claims.

## 3. Suggested three-minute video script

| Time | Screen | Narration / proof |
|---|---|---|
| 0:00–0:20 | Dashboard and campaign | “PostGenius Agent connects research, strategy, content and review in one workflow.” |
| 0:20–0:45 | Research Agent | Show source-backed research and explain Tavily retrieves evidence while Nemotron interprets it. |
| 0:45–1:10 | Strategy Agent | Show structured recommendations generated from campaign research. |
| 1:10–1:30 | Agent Center | Show an Agent Run, status, progress, and tasks. |
| 1:30–1:55 | Content Agent / Library | Show generated copy saved as a Content Asset. |
| 1:55–2:20 | Creative Agent / Library | Show Nemotron's creative brief and an image rendered by the separate image provider. |
| 2:20–2:40 | Approvals / Schedule | Show human oversight and guarded scheduling. |
| 2:40–3:00 | AI Usage / Architecture | Show Nebius/Nemotron integration, logs, and closing value proposition. |

**Accuracy:** If image generation, publishing, or analytics is not currently working, demonstrate a verified saved result and describe the limitation. Do not present static mockups as live execution.

## 4. Live click-by-click walkthrough

### Step A — Business and campaign

1. Sign in using the dedicated demo account.
2. Open the Businesses workspace and select the prepared business.
3. Open its marketing goal and campaign.
4. Explain that business context, target audience, and campaign objectives are persisted rather than pasted into each new prompt.

### Step B — Research

1. Navigate to the campaign's research page: `/campaigns/{campaign}/research`.
2. Open the completed Research Run.
3. Show retrieved sources, query history, and the resulting analysis.
4. Explain: **Tavily supplies external search results; NVIDIA Nemotron helps interpret the evidence.**

### Step C — Strategy

1. Navigate to `/campaigns/{campaign}/strategy`.
2. Open the completed strategy.
3. Show campaign actions and supporting evidence.
4. Explain how the application validates structured model output before accepting the strategy.

### Step D — Agent Run

1. Navigate to `/campaigns/{campaign}/agent` to create a run, or open an existing run in `/agent-center`.
2. Show run status and task records.
3. Explain the orchestrator and registered tools for retrieving business, campaign, research, strategy, and actions.

**If no Agent Runs appear:** Confirm research and strategy prerequisites, business membership, and run creation. The Content Agent cannot use a nonexistent run.

### Step E — Content Asset

1. Open `/content-agent`.
2. Select the appropriate Agent Run.
3. Enter a title, platform, content type, and instructions.
4. Generate a draft.
5. Wait for the queue job to complete; refresh if needed.
6. Open `/content-library` and show the resulting Content Asset.

**If no Content Asset appears:** Inspect the task status and queue job. A created task is not the same as a completed asset.

### Step F — Creative Asset

1. Open `/creative-agent`.
2. Select the same business.
3. Choose an eligible Content Asset (the workspace accepts draft or approved content).
4. Generate a creative if the image provider is configured.
5. Open `/creative-library` to show the creative specification and rendered image.

Explain that **Nemotron creates the visual direction and image prompt**; **OpenAI image generation** (if configured) produces the image.

### Step G — Review, schedule and analytics

1. Open `/approvals` and show the human review workflow.
2. Open `/schedule` to show planned publication records.
3. Open `/activity` and `/ai-usage` to show recorded execution/usage.
4. Open `/performance` to show available historical metrics.

Do not claim live social publishing unless the Meta integration and safety checks have been successfully tested.

## 5. Architecture talking points

**Language reasoning:** `AIManager` → `NebiusProvider` → Nebius Token Factory → NVIDIA Nemotron.

**Research:** `ResearchAgent` → Tavily search → evidence analysis.

**Content:** `GenerateContentTask` → `ContentAgent` → `ContentAsset`.

**Creative:** `CreativeAgent` → visual specification → separate `OpenAIImageProvider` → `CreativeAsset`.

**Reliability:** Laravel queues, persistent run statuses, JSON validation, usage logging, and manual approval.

**Safety:** Business membership checks, authorization policies, governance limits, and publishing kill switch.

## 6. Judge access instructions template

Provide the following **privately** through the submission platform:

```text
Application URL: https://pg-agent.itmarketz.com
Demo account email: [SHARE PRIVATELY]
Demo account password: [SHARE PRIVATELY]
Prepared business: [BUSINESS NAME]
Prepared campaign: [CAMPAIGN NAME]
Recommended workflow: Research → Strategy → Agent Center →
Content Library → Creative Library → Approvals → AI Usage
Known limitations: [STATE ANY CURRENT LIMITATIONS]
```

Do not add credentials to README, Git history, screenshots, or public issues.

## 7. Screenshots to include

Recommended sanitized captures:

- Dashboard and business context.
- Research result with sources.
- Strategy actions.
- Agent Run status.
- Content Agent and Content Asset.
- Creative Agent and Creative Asset.
- Approvals and Schedule.
- AI Usage and Performance.

Use descriptive names, e.g. `docs/screenshots/04-agent-run.png`. Only include images you actually captured; this guide does not contain screenshots.

## 8. Common demo problems

| Symptom | Check |
|---|---|
| AI request fails | Nebius key, exact model ID, endpoint, provider logs |
| Research stuck | `research` worker, Tavily key, failed jobs |
| Strategy invalid JSON | Output completeness, JSON validation, retry status |
| No Agent Runs | Research/strategy completion, campaign membership, creation step |
| No Content Assets | Content task completion and asset persistence |
| Creative dropdown empty | Business selection and eligible Content Asset |
| No generated image | `CREATIVE_IMAGE_PROVIDER`, key, creative worker, storage |
| Empty performance | Analytics permissions, sync status, historical metrics |
| Publication blocked | Publishing kill switch/governance; expected in a safe demo |

## 9. Suggested closing statement

> “PostGenius Agent turns disconnected marketing tasks into a traceable workflow. NVIDIA Nemotron, accessed through Nebius Token Factory, powers the reasoning behind research interpretation, strategy, content, and creative direction. External tools retrieve evidence or render images, while Laravel orchestration, persistent records, and human approvals connect the steps into a usable application.”

## 10. Final submission checklist

- [ ] Public source repository is sanitized.
- [ ] README and these four docs are committed.
- [ ] The configured Nemotron model is visible and a real request succeeds.
- [ ] Research, strategy, content, and creative results can be demonstrated.
- [ ] Demo account works and can access prepared records.
- [ ] Demo video is accessible in the visibility mode required by the competition.
- [ ] Submission includes the repository, demo, technical explanation, and required declarations.
- [ ] No credentials or customer data appear in public artifacts.

**Related documentation:** `docs/ARCHITECTURE.md`, `docs/AGENTS.md`, `docs/API_INTEGRATIONS.md`, `docs/NVIDIA_Nemotron_Integration.md`.
