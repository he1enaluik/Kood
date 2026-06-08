#!/bin/sh
set -e

if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate --force
fi

php artisan migrate --force
php artisan db:seed --force
php artisan storage:link 2>/dev/null || true

exec "$@"
