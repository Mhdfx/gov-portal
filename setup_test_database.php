<?php

/**
 * Setup Test Database Script
 * Creates the test database for MySQL testing
 */

$host = '127.0.0.1';
$port = 3306;
$username = 'root';
$password = '';
$database = 'government_portal_test';

try {
    // Connect to MySQL server (without database)
    $pdo = new PDO("mysql:host={$host};port={$port}", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    echo "✅ Test database '{$database}' created successfully!\n";
} catch (PDOException $e) {
    echo "❌ Error creating test database: " . $e->getMessage() . "\n";
    exit(1);
}














