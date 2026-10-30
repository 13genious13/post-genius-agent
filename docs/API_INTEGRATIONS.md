# PostGenius Agent — API Integrations

**Scope:** External services and internal integration boundaries confirmed in the final uploaded source archive.

## 1. Integration inventory

| Integration | Purpose | Implementation | Required for |
|---|---|---|---|
| Nebius Token Factory / NVIDIA Nemotron | Language reasoning and structured generation | `NebiusProvider`, `AIManager` | Research interpretation, strategy, content, creative direction |
| Tavily Search API | External web research | `TavilySearchProvider`, `WebSearchManager` | Research Agent |
| OpenAI Images API | Image rendering | `OpenAIImageProvider`, `ImageProviderFactory` | Optional Creative Agent image generation |
| Meta Graph API | Facebook/Instagram OAuth, publishing, insights | `MetaGraph`, `MetaPublisher`, `MetaInsightsClient` | Optional social publishing/analytics |
| Laravel MySQL/queues | State and background processing | Eloquent, jobs, scheduler | Core application |

**Distinction:** Nemotron generates language and structured specifications; OpenAI renders images; Tavily retrieves search results.

## 2. Nebius Token Factory

**Code:** `app/AI/Providers/NebiusProvider.php`, `config/ai.php`.

**Request:** HTTP `POST` to `chat/completions` relative to the configured `NEBIUS_BASE_URL`, which defaults to `https://api.tokenfactory.nebius.com/v1/`.

**Authentication:** Bearer token from `NEBIUS_API_KEY`.

**Payload:** `model`, `messages`, `temperature`, `max_tokens`; optionally `tools`, `tool_choice: auto`, and `response_format: {"type":"json_object"}`.

**Response:** Content from `choices[0].message.content`, optional tool calls, returned model identifier, and usage tokens. The adapter records latency and wraps the result as `AIResponse`.

**Environment:**

```dotenv
AI_DEFAULT_PROVIDER=nebius
AI_REQUEST_TIMEOUT=120
NEBIUS_API_KEY=YOUR_PRIVATE_KEY
NEBIUS_BASE_URL=https://api.tokenfactory.nebius.com/v1/
NEBIUS_DEFAULT_MODEL=nvidia/NVIDIA-Nemotron-3-Nano-30B-A3B
NEBIUS_TEMPERATURE=0.6
NEBIUS_MAX_TOKENS=4096
```

The model string is sourced from the uploaded `.env.example`; confirm actual access with your Nebius account. A successful configuration check is not proof of successful inference.

**Error handling:** Missing key/model and unsuccessful HTTP responses raise `AIProviderException`. `AIManager` logs failures through `AIUsageLogger`. Inspect safe error summaries rather than logging credentials or entire private prompts.

## 3. Tavily Search API

**Code:** `app/Research/Providers/TavilySearchProvider.php`, `config/research.php`.

**Request:** `POST {TAVILY_BASE_URL}/search` with Bearer authentication.

**Payload fields observed:** `query`, `search_depth`, `max_results`, `include_answer: false`, `include_raw_content: true`.

**Configuration:**

```dotenv
RESEARCH_SEARCH_PROVIDER=tavily
TAVILY_API_KEY=YOUR_PRIVATE_KEY
TAVILY_BASE_URL=https://api.tavily.com
TAVILY_SEARCH_DEPTH=advanced
TAVILY_MAX_RESULTS=6
RESEARCH_MAX_QUERIES=6
RESEARCH_RESULTS_PER_QUERY=6
RESEARCH_MAX_SOURCE_CONTENT=4000
```

**Behavior:** Timeout and bounded retries are configured in the adapter. Search results are converted into internal DTOs and evidence records.

**Caution:** Search results are external untrusted content. They may be outdated, incorrect, or prompt-injection attempts; the application should treat them as data, not commands.

## 4. OpenAI image generation

**Code:** `app/AI/Creative/OpenAIImageProvider.php`, `config/creative.php`.

**Request:** `POST https://api.openai.com/v1/images/generations` using Bearer authentication.

**Observed payload:** `model`, `prompt`, `size: 1024x1024`, `n: 1`.

**Observed response handling:** Reads `data[0].b64_json`, decodes bytes, returns image bytes and model identifier to the creative generation workflow.

**Configuration:**

```dotenv
CREATIVE_IMAGE_PROVIDER=disabled
CREATIVE_OPENAI_API_KEY=
CREATIVE_OPENAI_IMAGE_MODEL=gpt-image-1
CREATIVE_STORAGE_DISK=local
CREATIVE_MIN_BRAND_SCORE=75
```

The exact enabled provider key is determined by `ImageProviderFactory`. Set it only after confirming the factory's supported identifiers. An API key alone does not activate image generation.

**Security:** Store creative files on an appropriate disk; check access controls and media-sharing requirements. Never publish a raw private storage path.

## 5. Meta Graph API

**Code:** `app/Social/MetaGraph.php`, `app/Social/MetaPublisher.php`, `app/Http/Controllers/SocialConnectionController.php`, `app/Analytics/MetaInsightsClient.php`.

**OAuth flow:**
1. Authenticated user starts connection for a business they can access.
2. The app creates a random OAuth `state`, saves a hash and expiration in the session.
3. Meta redirects back with an authorization code.
4. The application verifies `state`, user, business, and expiry.
5. The code is exchanged for a token; the app retrieves authorized Pages and related Instagram business accounts.

**Publishing paths:**
- Facebook text posts: `POST /{page-id}/feed`.
- Facebook photo posts: `POST /{page-id}/photos`.
- Instagram image publishing: create media container via `/{ig-id}/media`, poll processing state, then `/{ig-id}/media_publish`.

**Configuration:**

```dotenv
META_APP_ID=
META_APP_SECRET=
META_REDIRECT_URI=https://your-domain.com/social/meta/callback
META_GRAPH_VERSION=v24.0
META_SCOPES=pages_show_list,pages_manage_posts,pages_read_engagement,instagram_basic,instagram_content_publish
SOCIAL_ALLOWED_MEDIA_HOSTS=media.your-domain.com
```

**Constraints:** Actual publishing requires approved app permissions, valid access tokens, a correctly configured callback, suitable business accounts, and reachable media URLs. Meta endpoints and metric availability can change with API versions.

**Safety:** `PublishingSafety` checks readiness, kill switch, and governance before publishing. Do not disable these safeguards for a public demo.

## 6. Analytics and optimization

**Code:** `app/Analytics/AnalyticsSyncService.php`, `MetaInsightsClient.php`, `MetricNormalizer.php`, `PerformanceSummary.php`; `app/Optimization/`.

**Purpose:** Retrieve permitted social metrics, normalize observations, store publication metric snapshots, and provide evidence for optimization recommendations.

**Environment:**

```dotenv
ANALYTICS_ENABLED=true
ANALYTICS_LOOKBACK_DAYS=30
ANALYTICS_SYNC_LIMIT=100
ANALYTICS_MIN_SYNC_INTERVAL=360
ANALYTICS_GRAPH_TIMEOUT=25
OPTIMIZATION_ENABLED=true
```

Metrics are not necessarily real-time; some depend on account type, permissions, platform, post type, and API version. Recorded AI costs may not equal provider invoices.

## 7. Internal routes and integration UI

Examples confirmed in the code:

| Route | Purpose |
|---|---|
| `/ai/playground` | Test configured language provider |
| `/campaigns/{campaign}/research` | Campaign research |
| `/campaigns/{campaign}/strategy` | Campaign strategy |
| `/campaigns/{campaign}/agent` | Create an Agent Run |
| `/agent-center` | Inspect runs |
| `/content-agent` | Generate content tasks |
| `/content-library` | Review Content Assets |
| `/creative-agent` | Generate creatives from eligible content |
| `/creative-library` | Review creative assets |
| `/approvals` | Approval workspace |
| `/schedule` | Scheduling workspace |
| `/performance` | Recorded performance |
| `/admin/system-settings` | Admin settings |

These are application routes, not a publicly documented REST API. Many require authentication, authorization, and/or throttling. Inspect `php artisan route:list` for exact HTTP methods and route names.

## 8. Integration test procedure

1. **Config:** Run `php artisan optimize:clear`; verify provider configuration without printing keys.
2. **Nebius:** Run the application's AI test command or Playground; inspect model and usage logging.
3. **Tavily:** Execute a research job and confirm source records.
4. **Strategy:** Generate a strategy and confirm valid structured output.
5. **Content:** Generate a task and verify a `ContentAsset`.
6. **Creative:** With a valid image provider, generate an image and verify storage/preview.
7. **Meta:** Test OAuth in a controlled environment; do not publish until permissions and safety are verified.
8. **Analytics:** Sync a known test publication and inspect snapshots.
9. **Failures:** Test bad key, model 404, rate limit, timeout, invalid JSON, expired OAuth token, and queue restart.

Useful commands:

```bash
php artisan route:list
php artisan queue:failed
php artisan schedule:list
php artisan phase21:check
```

## 9. Credential hygiene and public release

Never commit `.env`, API tokens, Meta access tokens, application logs, private user data, or production database dumps.

**Specific finding in the uploaded source:** `.env.example` includes a populated `APP_KEY` and `ADMIN_PASSWORD=12345678`. Sanitize those before publishing. If the key is in use, coordinate rotation with the deployment owner.

## 10. Source references

```text
config/ai.php
config/research.php
config/creative.php
config/social.php
config/analytics.php
app/AI/Providers/NebiusProvider.php
app/AI/Services/AIManager.php
app/Research/Providers/TavilySearchProvider.php
app/AI/Creative/OpenAIImageProvider.php
app/Social/MetaGraph.php
app/Social/MetaPublisher.php
app/Analytics/MetaInsightsClient.php
```

For system-level context, see `docs/ARCHITECTURE.md`.
