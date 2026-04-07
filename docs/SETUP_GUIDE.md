# Setup Guide

Complete guide for setting up the I.M System platform.

## Prerequisites

Before starting, ensure you have the following installed:

- **PHP 8.2+** with extensions:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML
- **Composer** (PHP package manager)
- **Node.js 18+** and **npm**
- **MySQL 8.0+** or **MariaDB 10.3+**
- **Docker & Docker Compose** (optional, for containerized setup)

## Quick Start (Docker)

### Step 1: Clone Repository
```bash
git clone <repository-url>
cd government-portal
```

### Step 2: Configure Environment
```bash
cp .env.example .env
```

Edit `.env` and set:
```env
APP_NAME="I.M System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8001

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=government_portal
DB_USERNAME=root
DB_PASSWORD=password
```

### Step 3: Start Services
```bash
docker-compose up -d
```

### Step 4: Install Dependencies
```bash
docker-compose exec app composer install
docker-compose exec app npm install
```

### Step 5: Generate Application Key
```bash
docker-compose exec app php artisan key:generate
```

### Step 6: Run Migrations
```bash
docker-compose exec app php artisan migrate --force
```

### Step 7: Build Assets
```bash
docker-compose exec app npm run build
```

### Step 8: Access Application
- **Application**: http://localhost:8001
- **phpMyAdmin**: http://localhost:8080

## Manual Setup (Without Docker)

### Step 1: Install PHP Dependencies
```bash
composer install
```

### Step 2: Install Node Dependencies
```bash
npm install
```

### Step 3: Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

### Step 4: Configure Database

Create a MySQL database:
```sql
CREATE DATABASE government_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=government_portal
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 5: Run Migrations
```bash
php artisan migrate
```

### Step 6: Build Frontend Assets
```bash
npm run build
```

### Step 7: Set Permissions
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache  # Linux
```

### Step 8: Start Development Server
```bash
php artisan serve
```

Access at: http://localhost:8000

## Development Workflow

### Running Tests
```bash
php artisan test
```

### Watching Assets (Development)
```bash
npm run dev
```

### Building Assets (Production)
```bash
npm run build
```

### Clearing Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Creating Test Users

### Using Tinker
```bash
php artisan tinker
```

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'username' => 'testuser',
    'email' => 'test@example.com',
    'password' => Hash::make('password'),
    'role' => 'user',
    'verification_status' => 'verified',
]);
```

### Using Seeders
```bash
php artisan db:seed
```

## Troubleshooting

### Port Already in Use
If port 8001 is in use, change it in `.env`:
```env
APP_PORT=8002
```

### Database Connection Failed
1. Verify MySQL is running
2. Check credentials in `.env`
3. Ensure database exists
4. Check user permissions

### Assets Not Loading
1. Run `npm run build`
2. Check `public/build/` exists
3. Verify `public/hot` doesn't exist (unless using dev server)
4. Clear cache: `php artisan view:clear`

### Permission Denied
```bash
chmod -R 775 storage bootstrap/cache
```

## Next Steps

After setup:
1. Create admin user
2. Configure email settings (if needed)
3. Review security settings
4. Set up backup procedures
5. Configure production environment variables














