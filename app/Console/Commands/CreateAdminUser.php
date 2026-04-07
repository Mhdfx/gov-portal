<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {--email=admin@boiema.ma} {--password=admin123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user for the Boiema platform';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error('User with email ' . $email . ' already exists!');
            return 1;
        }

        // Create admin user
        $user = User::create([
            'username' => 'admin',
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'main_admin',
            'region' => 'Rabat-Salé-Kénitra',
            'city' => 'Rabat',
            'verification_status' => 'verified',
            'email_verified_at' => now(),
        ]);

        // Create user profile
        UserProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Admin',
            'last_name' => 'Principal',
            'phone' => '+212600000000',
            'address' => 'Administration Centrale',
            'city' => 'Rabat',
            'region' => 'Rabat-Salé-Kénitra',
            'postal_code' => '10000',
            'country' => 'Morocco',
            'profile_type' => 'institution',
        ]);

        $this->info('Admin user created successfully!');
        $this->info('Email: ' . $email);
        $this->info('Password: ' . $password);
        $this->info('Role: main_admin');

        return 0;
    }
}






























