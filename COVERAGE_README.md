# Code Coverage Setup

## Running Tests with Coverage

To generate code coverage reports, run:

```bash
php artisan test --coverage
```

Or using PHPUnit directly:

```bash
vendor/bin/phpunit --coverage-html coverage
```

## Coverage Reports

- **HTML Report**: Generated in `coverage/` directory
- **Text Report**: Displayed in terminal

## Target Coverage

- **Current Target**: 80%+
- **Focus Areas**: Controllers, Services, Models

## Viewing Coverage

1. Open `coverage/index.html` in your browser
2. Navigate through files to see line-by-line coverage
3. Identify untested code paths

## Excluded from Coverage

- Console commands (`app/Console`)
- Exception handlers (`app/Exceptions`)
- Third-party vendor code

## Running Specific Test Suites

```bash
# Unit tests only
php artisan test --testsuite=Unit

# Feature tests only
php artisan test --testsuite=Feature

# Specific test file
php artisan test tests/Feature/FileUploadTest.php
```














