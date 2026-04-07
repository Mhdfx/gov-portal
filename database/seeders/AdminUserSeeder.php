<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed a default admin user for quick logins.
     */
    public function run(): void
    {
        // Default main admin (existing)
        User::updateOrCreate(
            ['username' => 'mehdi'],
            [
                'email' => 'mehdi@example.com',
                'password' => Hash::make('aloalo'),
                'role' => 'main_admin',
                'verification_status' => 'verified',
                'two_factor_enabled' => false,
            ]
        );

        // Test accounts for all roles
        $accounts = [
            ['username' => 'admin', 'email' => 'admin@example.com', 'role' => 'main_admin'],
            ['username' => 'institutional_admin', 'email' => 'institutional_admin@example.com', 'role' => 'institutional_admin'],
            ['username' => 'sectoral_admin', 'email' => 'sectoral_admin@example.com', 'role' => 'sectoral_admin'],
            ['username' => 'testcompany', 'email' => 'testcompany@example.com', 'role' => 'company'],
            ['username' => 'testuser', 'email' => 'testuser@example.com', 'role' => 'user'],
            ['username' => 'testcandidate', 'email' => 'testcandidate@example.com', 'role' => 'candidate'],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['username' => $account['username']],
                [
                    'email' => $account['email'],
                    'password' => Hash::make('password'),
                    'role' => $account['role'],
                    'verification_status' => 'verified',
                    'two_factor_enabled' => false,
                ]
            );
        }
    }
}