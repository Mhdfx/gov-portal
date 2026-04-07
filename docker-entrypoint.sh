#!/bin/bash
set -e

echo "Starting Laravel application..."

# Wait for database to be ready (with timeout)
echo "Waiting for database..."
timeout=60
counter=0
until php artisan db:show --quiet 2>/dev/null || [ $counter -eq $timeout ]; do
    echo "Database is unavailable - sleeping (attempt $counter/$timeout)"
    sleep 2
    counter=$((counter+2))
done

if [ $counter -eq $timeout ]; then
    echo "Warning: Database connection timeout. Continuing anyway..."
else
    echo "Database is ready!"
fi

# Run migrations if needed
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force || echo "Migration failed, continuing..."
fi

# Clear caches
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true
php artisan route:clear || true

# Optimize for production
if [ "$APP_ENV" = "production" ]; then
    echo "Optimizing for production..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Start the server
echo "Starting Laravel server on 0.0.0.0:8001..."
exec php artisan serve --host=0.0.0.0 --port=8001

