#!/usr/bin/env bash
set -Eeuo pipefail
cd "$(dirname "$0")/.."
if [[ ! -f artisan || ! -f vendor/autoload.php ]]; then echo 'Run from Laravel root after composer install' >&2; exit 2; fi
printf '\n[1] PHP syntax (application, routes, tests)\n'
find app routes tests -type f -name '*.php' -print0 | while IFS= read -r -d '' file; do php -l "$file" >/dev/null || exit 1; done
printf '\n[2] Laravel routes\n'
php artisan route:list --except-vendor
printf '\n[3] Schedule registrations\n'
php artisan schedule:list
printf '\n[4] Database migration status (read only)\n'
php artisan migrate:status
printf '\n[5] Phase 21 security tests (TEST DB ONLY)\n'
if [[ "${APP_ENV:-}" == 'production' ]]; then echo 'Refusing automated tests in production' >&2; exit 3; fi
php artisan test --filter=Phase21SecurityTest
printf '\n[6] Release readiness report\n'
php artisan phase21:check
