#!/bin/bash
set -e

echo "🚀 Setting up Government Portal..."

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env 2>/dev/null || cp .env.docker.example .env
fi

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install

# Install Node dependencies
echo "📦 Installing Node dependencies..."
npm install

# Build assets
echo "🏗️  Building assets..."
npm run build

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

# Create storage directories
echo "📁 Creating storage directories..."
php artisan storage:link || true

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "✅ Setup complete!"
echo ""
echo "To start the server, run:"
echo "  php artisan serve"
echo ""
echo "Or with Docker:"
echo "  docker-compose up -d"




