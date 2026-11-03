#!/bin/sh
# Container start: prepare Laravel, then run Apache in the foreground.
set -e

export PORT="${PORT:-8080}"

# mod_php needs the prefork MPM; Apache refuses to start if more than one MPM is enabled.
rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.*
cd /var/www/html

if [ -z "$APP_KEY" ]; then
  echo "APP_KEY is not set. Generate one with: php artisan key:generate --show" >&2
  exit 1
fi

# Cache config/routes/views for speed (reads the current environment variables).
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Database: apply migrations, then create the admin user if configured.
php artisan migrate --force
php artisan app:create-admin

chown -R www-data:www-data storage bootstrap/cache
exec apache2-foreground
