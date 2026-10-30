<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\{DB, Schema, Route};
use Throwable;

/** Read-only release checks. No API requests, writes, or publishing. */
final class Phase21Readiness extends Command
{
    protected $signature = 'phase21:check {--strict : Treat warnings as failures} {--json : Emit machine-readable report}';
    protected $description = 'Read-only PostGenius V1 release readiness checks';

    public function handle(): int
    {
        $items = [];
        $add = static function (string $id, string $level, bool $pass, string $message) use (&$items): void {
            $items[] = compact('id', 'level', 'pass', 'message');
        };

        $add('debug_disabled', 'critical', !config('app.debug'), 'APP_DEBUG must be false');
        $add('production_env', 'critical', app()->environment('production'), 'APP_ENV must be production on live server');
        $add('app_key', 'critical', filled(config('app.key')), 'APP_KEY must exist (do not rotate casually)');
        $add('https', 'critical', str_starts_with((string) config('app.url'), 'https://'), 'APP_URL must use HTTPS');
        $add('queue_async', 'critical', !in_array(config('queue.default'), ['sync', 'null'], true), 'Use supervised asynchronous queue workers');
        $add('publishing_safe', 'critical', config('production_readiness.publish_kill_switch', true) === true, 'Keep publishing kill switch ON until signed-off');
        $add('governance', 'critical', config('agent_governance.enabled') === true, 'Enable agent governance');
        $add('health_token', 'warning', filled(config('production_readiness.health_token')), 'Configure a secret health endpoint token');
        $add('session_secure', 'warning', config('session.secure') === true, 'Use secure session cookies under HTTPS');

        try {
            DB::select('SELECT 1');
            $add('database', 'critical', true, 'Database responds');
            foreach (['users','businesses','campaigns','agent_runs','research_runs','content_assets','creative_assets','social_publications','ai_request_logs'] as $table) {
                $add('table_'.$table, 'critical', Schema::hasTable($table), 'Required table '.$table);
            }
        } catch (Throwable $e) {
            $add('database', 'critical', false, 'Database connection failed; see private server logs');
        }

        foreach (['dashboard','ai.playground'] as $name) {
            $add('route_'.$name, 'critical', Route::has($name), 'Route '.$name.' must exist');
        }
        foreach (['content-agent.index','creative-agent.index','activity.index','ai-usage.index','approvals.index','schedule.index','performance.index'] as $name) {
            $add('route_'.$name, 'warning', Route::has($name), 'Check installed phase route name: '.$name);
        }

        $console = base_path('routes/console.php');
        if (is_file($console)) {
            $source = file_get_contents($console) ?: '';
            $legacy = str_contains($source, 'Schedule::job(new DispatchDueSocialPublications');
            $safe = str_contains($source, 'Schedule::job(new DispatchDuePublicationsSafely');
            $add('single_safe_scheduler', 'critical', !$legacy && $safe, 'Exactly the safe due-publication dispatcher must be scheduled');
        } else {
            $add('single_safe_scheduler', 'critical', false, 'Missing routes/console.php');
        }

        $failures = array_values(array_filter($items, fn ($x) => !$x['pass'] && ($x['level'] === 'critical' || $this->option('strict'))));
        if ($this->option('json')) {
            $this->line(json_encode(['ready' => count($failures) === 0, 'checks' => $items], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        } else {
            $this->table(['Check', 'Severity', 'Result', 'Requirement'], array_map(fn ($x) => [$x['id'], $x['level'], $x['pass'] ? 'PASS' : 'FAIL', $x['message']], $items));
            $this->line(count($failures) === 0 ? 'Readiness checks passed; manual sign-off still required.' : count($failures).' blocking checks failed.');
        }
        return count($failures) === 0 ? self::SUCCESS : self::FAILURE;
    }
}
