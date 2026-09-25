#!/bin/sh
set -eu

cd /var/www/html

if [ "${APP_ENV:-}" = "production" ]; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan storage:link
fi

exec "$@"