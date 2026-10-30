# Safe deployment and rollback

1. Take verified code and DB backups. Record release commit/tag and migration status.
2. Deploy to staging; keep PUBLISH_KILL_SWITCH=true. Run Phase21 checks and manual QA.
3. Deploy production with maintenance window if required; deploy code and build assets atomically.
4. Avoid `migrate:fresh`, `db:wipe`, `queue:clear`, and automatic migration rollback. Phase 21 adds no migrations.
5. Run config/route/view cache commands and `queue:restart`. Confirm Supervisor workers and scheduler.
6. If critical regression occurs: enable kill switch, stop affected queue workers, restore prior code release, clear/rebuild caches, restart workers. Restore DB only when approved after assessing writes since backup.
7. Verify `/dashboard`, `/internal/agent-health` with secret bearer token, queues, scheduler, logs, and tenant isolation.
8. Re-enable live publishing only after written approval and a single safe canary post.

Production env baseline: APP_ENV=production, APP_DEBUG=false, APP_URL=https://..., SESSION_SECURE_COOKIE=true, PUBLISH_KILL_SWITCH=true initially. Do not commit .env, API keys, logs, or backups.
