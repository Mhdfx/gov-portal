# I.M System - Government Portal Platform

A comprehensive digital platform for connecting project carriers, auto-entrepreneurs, investors, companies, and public institutions in Morocco.

## 🚀 Features

- **Form Submissions**: Six different submission forms (Investment, Project Carrier, Idea Carrier, Auto-Entrepreneur, INDH, Training)
- **Multi-Role Dashboards**: Separate dashboards for users, admins, and sectoral admins
- **File Uploads**: Secure file upload system for documents
- **Authentication System**: Role-based access control
- **Submission Tracking**: Track submission status and history
- **Search Functionality**: Global search across submissions and companies

## 📋 Requirements

- PHP 8.2+
- Composer
- Node.js 18+ and npm
- MySQL 8.0+
- Docker & Docker Compose (for containerized deployment)

## 🛠️ Installation

### Using Docker (Recommended)

1. Clone the repository:
```bash
git clone <repository-url>
cd government-portal
```

2. Copy environment file:
```bash
cp .env.example .env
```

3. Update `.env` with your configuration:
```env
APP_NAME="I.M System"
APP_URL=http://localhost:8001
DB_DATABASE=government_portal
DB_USERNAME=root
DB_PASSWORD=password
```

4. Start Docker containers:
```bash
docker-compose up -d
```

5. Install dependencies and run migrations:
```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --force
docker-compose exec app npm install
docker-compose exec app npm run build
```

6. Access the application:
- Application: http://localhost:8001
- phpMyAdmin: http://localhost:8080

### Manual Installation

1. Clone the repository and install dependencies:
```bash
composer install
npm install
```

2. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
```

3. Configure database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=government_portal
DB_USERNAME=root
DB_PASSWORD=
```

4. Run migrations:
```bash
php artisan migrate
```

5. Build assets:
```bash
npm run build
```

6. Start development server:
```bash
php artisan serve
```

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

Run specific test suites:
```bash
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

## 📚 Documentation

- [API Documentation](docs/API_DOCUMENTATION.md)
- [Testing Guide](tests/README.md)
- [Improvement Plan](PROJECT_IMPROVEMENT_PLAN.md)

## 👥 User Roles

- **User**: Submit forms and track submissions
- **Admin**: Full system access, manage all submissions
- **Sectoral Admin**: Manage submissions for specific sectors
- **Institutional Admin**: Manage institutional submissions
- **Company**: Business account management

## 🔐 Default Test Accounts

All test accounts use password: `password`

- Admin: `admin`
- Sectoral Admin: `sectoral_admin`
- Regular User: `testuser`
- Company: `testcompany`

## 📁 Project Structure

```
government-portal/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Application controllers
│   │   ├── Middleware/       # Custom middleware
│   │   └── Requests/         # Form request validation
│   └── Models/               # Eloquent models
├── database/
│   ├── migrations/           # Database migrations
│   └── factories/            # Model factories
├── resources/
│   ├── views/                # Blade templates
│   ├── css/                  # Stylesheets
│   └── js/                   # JavaScript files
├── routes/
│   └── web.php               # Web routes
├── tests/                     # Test suite
└── docs/                      # Documentation
```

## 🚀 Development

### Running in Development Mode

```bash
# Start Laravel server
php artisan serve

# Start Vite dev server (in another terminal)
npm run dev
```

### Building for Production

```bash
npm run build
```

## 🔒 Security Features

- Content Security Policy (CSP) headers
- CSRF protection
- XSS protection
- SQL injection prevention
- File upload validation
- Role-based access control

## 📝 Form Types

1. **Investment Form**: For investment requests
2. **Project Carrier Form**: For project proposals
3. **Idea Carrier Form**: For innovative ideas
4. **Auto-Entrepreneur Form**: For auto-entrepreneur registration
5. **INDH Form**: For INDH project submissions
6. **Training Form**: For training requests

## 🐛 Troubleshooting

### Styles Not Loading
- Ensure `npm run build` has been executed
- Check that `public/build/` directory exists
- Verify Vite manifest file exists

### Database Connection Issues
- Verify database credentials in `.env`
- Ensure MySQL service is running
- Check database exists

### Permission Issues
- Ensure `storage/` and `bootstrap/cache/` are writable
- Run: `chmod -R 775 storage bootstrap/cache`

## 📄 License

This project is proprietary software.

## 🤝 Contributing

Please refer to the [Improvement Plan](PROJECT_IMPROVEMENT_PLAN.md) for areas that need work.

## 📞 Support

For issues and questions, please refer to the project documentation or contact the development team.
