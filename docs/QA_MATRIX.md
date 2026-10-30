# Phase 21 V1 acceptance matrix

Record tester, date, environment, evidence URL/screenshot, expected/actual outcome, and defect ID for each case. Do not use live social accounts for destructive tests.

| ID | Scenario | Expected outcome |
|---|---|---|
| AUTH-01 | Guest opens dashboard / AI pages | Redirect to login |
| AUTH-02 | Deactivated user signs in | Access denied |
| TEN-01 | User A requests business B resources | 403/404; no data leakage |
| TEN-02 | User A edits another tenant's campaign | Rejected; DB unchanged |
| TEN-03 | Export activity / AI usage as tenant A | No tenant B rows |
| AI-01 | Nebius text generation valid response | Correct provider/model, logged usage |
| AI-02 | Nebius returns 429 / 5xx | Safe error, bounded retries |
| AI-03 | Malformed JSON from Strategy Agent | Error logged, no false success |
| RES-01 | Tavily research produces sources | Sources linked to correct research run |
| RES-02 | Research job record deleted | Job fails safely, no cross-tenant data |
| CNT-01 | Content generation creates draft | Editable and attributable to campaign |
| CNT-02 | Content approval / revision | Correct state transitions and audit trail |
| CRT-01 | Creative generation | Private asset, preview authorized only |
| CRT-02 | Creative generation error | No broken successful asset |
| PUB-01 | Publishing kill switch ON | No external publication |
| PUB-02 | Unapproved publication | Blocked by safety gate |
| PUB-03 | Two workers process same due post | No duplicate provider post |
| PUB-04 | Scheduled post date changed | Correct time zone / schedule |
| ANA-01 | Metric sync | Provider values and timestamps accurate |
| ANA-02 | Empty analytics period | No misleading sample metrics |
| SET-01 | Non-admin changes system settings | Denied |
| SET-02 | Settings update | Validated, saved and audited |
| SEC-01 | Production exception | No stack trace or secrets in browser |
| SEC-02 | Internal health without bearer token | 404 |
| OPS-01 | Scheduler and queue workers | Supervised and recover after restart |
| OPS-02 | Database backup and restore | Restore verified on staging |
| UI-01 | Mobile 360px and tablet | No overflow, controls accessible |
| UI-02 | All sidebar links | Working page or deliberately disabled |

Release gate: all critical cases PASS, no critical/high defects open, rollback tested, owner signs off.
