# PowerShell setup script for Windows
Write-Host "🚀 Setting up Government Portal..." -ForegroundColor Cyan

# Check if .env exists
if (-not (Test-Path .env)) {
    Write-Host "📝 Creating .env file..." -ForegroundColor Yellow
    if (Test-Path .env.example) {
        Copy-Item .env.example .env
    } elseif (Test-Path .env.docker.example) {
        Copy-Item .env.docker.example .env
    }
}

# Install PHP dependencies
Write-Host "📦 Installing PHP dependencies..." -ForegroundColor Yellow
composer install

# Install Node dependencies
Write-Host "📦 Installing Node dependencies..." -ForegroundColor Yellow
npm install

# Build assets
Write-Host "🏗️  Building assets..." -ForegroundColor Yellow
npm run build

# Generate application key if not set
$envContent = Get-Content .env -Raw
if ($envContent -notmatch "APP_KEY=base64:") {
    Write-Host "🔑 Generating application key..." -ForegroundColor Yellow
    php artisan key:generate
}

# Create storage directories
Write-Host "📁 Creating storage directories..." -ForegroundColor Yellow
php artisan storage:link 2>$null

# Clear caches
Write-Host "🧹 Clearing caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

Write-Host "✅ Setup complete!" -ForegroundColor Green
Write-Host ""
Write-Host "To start the server, run:" -ForegroundColor Cyan
Write-Host "  php artisan serve" -ForegroundColor White
Write-Host ""
Write-Host "Or with Docker:" -ForegroundColor Cyan
Write-Host "  docker-compose up -d" -ForegroundColor White




