# Testing Documentation

This directory contains all automated tests for the I.M System platform.

## Test Structure

- **Feature Tests** (`tests/Feature/`): Test complete user workflows and feature functionality
- **Unit Tests** (`tests/Unit/`): Test individual components, models, and services in isolation

## Running Tests

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suite
```bash
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

### Run Specific Test File
```bash
php artisan test tests/Feature/FormSubmissionTest.php
```

### Run Specific Test Method
```bash
php artisan test --filter test_user_can_submit_investment_form
```

## Test Coverage

### Current Test Files

1. **FormSubmissionTest.php**
   - Investment form submission
   - Project Carrier form submission
   - Auto-Entrepreneur form submission
   - Form validation testing

2. **AuthenticationTest.php**
   - User login/logout
   - Authentication middleware
   - Redirect after login

## Test Database

Tests use an in-memory SQLite database configured in `phpunit.xml`. The database is reset before each test using `RefreshDatabase` trait.

## Writing New Tests

### Feature Test Example
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_does_something()
    {
        // Arrange
        // Act
        // Assert
    }
}
```

### Best Practices

1. Use descriptive test method names
2. Follow AAA pattern (Arrange, Act, Assert)
3. Use factories for test data
4. Test both success and failure scenarios
5. Keep tests isolated and independent
6. Use meaningful assertions














