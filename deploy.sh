#!/bin/bash

# Boiema Platform Deployment Script
# This script automates the deployment process for the Boiema platform

set -e

echo "🚀 Starting Boiema Platform Deployment..."

# Configuration
APP_NAME="boiema-platform"
APP_DIR="/var/www/boiema-platform"
BACKUP_DIR="/var/backups/boiema-platform"
LOG_FILE="/var/log/boiema-deployment.log"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Logging function
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1" | tee -a $LOG_FILE
}

error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a $LOG_FILE
    exit 1
}

warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a $LOG_FILE
}

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   error "This script should not be run as root for security reasons"
fi

# Check if required commands exist
check_requirements() {
    log "Checking system requirements..."
    
    local required_commands=("php" "composer" "npm" "mysql" "redis-cli" "git")
    
    for cmd in "${required_commands[@]}"; do
        if ! command -v $cmd &> /dev/null; then
            error "$cmd is required but not installed"
        fi
    done
    
    log "All requirements satisfied"
}

# Create backup
create_backup() {
    log "Creating backup of current deployment..."
    
    if [ -d "$APP_DIR" ]; then
        local backup_name="backup-$(date +%Y%m%d-%H%M%S)"
        local backup_path="$BACKUP_DIR/$backup_name"
        
        mkdir -p "$BACKUP_DIR"
        
        # Backup database
        log "Backing up database..."
        mysqldump -u root -p boiema_platform > "$backup_path-database.sql"
        
        # Backup application files
        log "Backing up application files..."
        cp -r "$APP_DIR" "$backup_path-app"
        
        # Backup uploads
        if [ -d "$APP_DIR/storage/app/public" ]; then
            cp -r "$APP_DIR/storage/app/public" "$backup_path-uploads"
        fi
        
        log "Backup created: $backup_path"
    else
        warning "No existing deployment found, skipping backup"
    fi
}

# Update application code
update_code() {
    log "Updating application code..."
    
    cd "$APP_DIR"
    
    # Pull latest changes
    git pull origin main
    
    log "Code updated successfully"
}

# Install/update dependencies
install_dependencies() {
    log "Installing dependencies..."
    
    cd "$APP_DIR"
    
    # Install PHP dependencies
    composer install --no-dev --optimize-autoloader
    
    # Install Node.js dependencies
    npm ci --production
    
    # Build assets
    npm run build
    
    log "Dependencies installed successfully"
}

# Run database migrations
run_migrations() {
    log "Running database migrations..."
    
    cd "$APP_DIR"
    
    php artisan migrate --force
    
    log "Database migrations completed"
}

# Clear and cache application
optimize_application() {
    log "Optimizing application..."
    
    cd "$APP_DIR"
    
    # Clear caches
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    
    # Cache configurations
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    # Optimize autoloader
    composer dump-autoload --optimize
    
    log "Application optimized"
}

# Set proper permissions
set_permissions() {
    log "Setting proper permissions..."
    
    cd "$APP_DIR"
    
    # Set ownership
    sudo chown -R www-data:www-data .
    
    # Set directory permissions
    find . -type d -exec chmod 755 {} \;
    
    # Set file permissions
    find . -type f -exec chmod 644 {} \;
    
    # Set executable permissions for scripts
    chmod +x artisan
    
    # Set storage permissions
    chmod -R 775 storage
    chmod -R 775 bootstrap/cache
    
    log "Permissions set successfully"
}

# Restart services
restart_services() {
    log "Restarting services..."
    
    # Restart PHP-FPM
    sudo systemctl restart php8.1-fpm
    
    # Restart Nginx
    sudo systemctl restart nginx
    
    # Restart Redis
    sudo systemctl restart redis
    
    # Restart MySQL
    sudo systemctl restart mysql
    
    log "Services restarted successfully"
}

# Health check
health_check() {
    log "Performing health check..."
    
    # Check if application is responding
    local response=$(curl -s -o /dev/null -w "%{http_code}" http://localhost)
    
    if [ "$response" = "200" ]; then
        log "Health check passed - Application is responding"
    else
        error "Health check failed - Application returned HTTP $response"
    fi
}

# Cleanup old backups
cleanup_backups() {
    log "Cleaning up old backups..."
    
    # Keep only last 5 backups
    cd "$BACKUP_DIR"
    ls -t | tail -n +6 | xargs -r rm -rf
    
    log "Old backups cleaned up"
}

# Main deployment function
main() {
    log "Starting deployment process..."
    
    check_requirements
    create_backup
    update_code
    install_dependencies
    run_migrations
    optimize_application
    set_permissions
    restart_services
    health_check
    cleanup_backups
    
    log "🎉 Deployment completed successfully!"
    log "Application is now live and ready to serve requests"
}

# Run main function
main "$@"






























