<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-passwords {--password=password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all user passwords to a specified password (default: password)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $password = $this->option('password');
        
        $this->info('Updating all user passwords to: ' . $password);
        
        $users = User::all();
        $count = 0;
        
        foreach ($users as $user) {
            $user->update([
                'password' => Hash::make($password)
            ]);
            $count++;
        }
        
        $this->info("Successfully updated {$count} user passwords.");
        
        // Display login credentials for admin users
        $adminUsers = User::whereIn('role', ['main_admin', 'institutional_admin', 'sectoral_admin'])->get();
        
        if ($adminUsers->count() > 0) {
            $this->info("\nAdmin Login Credentials:");
            $this->info("========================");
            
            foreach ($adminUsers as $admin) {
                $this->info("Username: {$admin->username}");
                $this->info("Password: {$password}");
                $this->info("Role: {$admin->role}");
                $this->info("Email: {$admin->email}");
                $this->info("---");
            }
        }
        
        return 0;
    }
}






























