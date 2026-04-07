#!/bin/bash
set -e

echo "=== Starting Docker Entrypoint Script ==="

# Wait for database to be ready
echo "Waiting for database connection..."
max_attempts=30
attempt=0

# Function to check database connection using PHP
check_db() {
    php artisan db:show 2>/dev/null || php artisan migrate:status 2>/dev/null || php -r "try { new PDO('mysql:host=${DB_HOST:-db};port=${DB_PORT:-3306};dbname=${DB_DATABASE:-boiema_platform}', '${DB_USERNAME:-boiema_user}', '${DB_PASSWORD:-boiema_password}'); exit(0); } catch (Exception \$e) { exit(1); }" 2>/dev/null
}

until check_db; do
  attempt=$((attempt + 1))
  if [ $attempt -ge $max_attempts ]; then
    echo "❌ Database connection failed after $max_attempts attempts"
    exit 1
  fi
  >&2 echo "Database is unavailable - sleeping (attempt $attempt/$max_attempts)"
  sleep 2
done

echo "✅ Database is ready!"

# Generate application key if not exists
if [ ! -f .env ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env 2>/dev/null || true
fi

# Check if app key exists and generate if needed
APP_KEY=$(php artisan tinker --execute="echo config('app.key');" 2>/dev/null || echo "")
if [ -z "$APP_KEY" ] || [ "$APP_KEY" == "base64:null" ] || [ "$APP_KEY" == "" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
else
    echo "Application key already exists"
fi

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Ensure session table exists (sessions table is created in users migration, but verify)
echo "Verifying session table exists..."
php artisan migrate --force

# Cache configuration for production
if [ "$APP_ENV" = "production" ]; then
    echo "Caching configuration for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
else
    echo "Clearing caches for development..."
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
fi

# Clear and optimize
php artisan optimize:clear
php artisan optimize

# Set permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo "✅ Docker entrypoint completed successfully!"
echo "=== Starting Application ==="

# Execute the main command
exec "$@"

