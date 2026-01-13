#!/bin/sh
set -e

# Default PORT to 8080 if not set (Render/Railway will set this)
PORT=${PORT:-8080}

echo "=========================================="
echo "Starting Laravel on port $PORT"
echo "=========================================="

# Clear config cache
php artisan config:clear

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Run seeders (ignore errors if already seeded)
echo "Running seeders..."
php artisan db:seed --class=AdminSeeder --force || true
php artisan db:seed --class=CategorySeeder --force || true
php artisan db:seed --class=BookSeeder --force || true

# Cache for production
php artisan config:cache
php artisan route:cache

echo "=========================================="
echo "Starting PHP Server on 0.0.0.0:$PORT"
echo "=========================================="

# Start PHP built-in server (works on both Render and Railway)
exec php artisan serve --host=0.0.0.0 --port=$PORT
