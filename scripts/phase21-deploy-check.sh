#!/usr/bin/env bash
set -Eeuo pipefail
cd "$(dirname "$0")/.."
[[ -f artisan ]] || { echo 'Run from Laravel root'; exit 2; }
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan phase21:check --strict
php artisan schedule:list
php artisan queue:failed
printf '\nNOTE: Verify supervisor workers, cron, HTTPS, backups and credentials manually.\n'
