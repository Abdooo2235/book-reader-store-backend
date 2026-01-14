#!/bin/sh
set -e

# Default PORT to 8080 if not set
PORT=${PORT:-8080}

echo "=========================================="
echo "Laravel Production Server"
echo "PORT: $PORT"
echo "=========================================="

# Replace PORT placeholder in nginx config
envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

# Run Laravel migrations
echo "Running migrations..."
php artisan config:clear
php artisan migrate --force || echo "Migration failed, continuing..."

# Run seeders (ignore errors if already seeded)
echo "Running seeders..."
php artisan db:seed --class=AdminSeeder --force 2>/dev/null || true
php artisan db:seed --class=CategorySeeder --force 2>/dev/null || true
php artisan db:seed --class=BookSeeder --force 2>/dev/null || true

# Cache config for production
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "=========================================="
echo "Starting Nginx + PHP-FPM via Supervisor"
echo "=========================================="

# Start supervisor (manages nginx and php-fpm)
exec /usr/bin/supervisord -n -c /etc/supervisord.conf
