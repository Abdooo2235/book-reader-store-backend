#!/bin/sh
set -e

# Default PORT to 8080 if not set
export PORT=${PORT:-8080}

echo "Starting Laravel application on port $PORT..."

# Replace ${PORT} in nginx config with actual port value
envsubst '${PORT}' < /etc/nginx/nginx.conf > /tmp/nginx.conf
mv /tmp/nginx.conf /etc/nginx/nginx.conf

# Run Laravel migrations
echo "Running migrations..."
php artisan config:clear
php artisan migrate --force

# Run seeders (ignore errors if already seeded)
echo "Running seeders..."
php artisan db:seed --class=AdminSeeder --force || true
php artisan db:seed --class=CategorySeeder --force || true
php artisan db:seed --class=BookSeeder --force || true

# Cache config for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting Supervisor (Nginx + PHP-FPM)..."

# Start supervisor which manages nginx and php-fpm
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
