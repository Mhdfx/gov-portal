<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== USERS IN DATABASE ===\n\n";

$users = App\Models\User::all(['id', 'username', 'role', 'verification_status']);

foreach ($users as $user) {
    echo "ID: {$user->id} | Username: {$user->username} | Role: {$user->role} | Status: {$user->verification_status}\n";
}

echo "\n=== TEST CREDENTIALS ===\n";
echo "Admin: admin / password\n";
echo "Institutional Admin: institutional_admin / password\n";
echo "Sectoral Admin: sectoral_admin / password\n";
echo "User: testuser / password\n";
echo "Company: testcompany / password\n";

echo "\n=== END ===\n";






























