<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\UserProfile;

// Find sectoral admin
$sectoralAdmin = User::where('role', 'sectoral_admin')->first();

if (!$sectoralAdmin) {
    echo "No sectoral admin found. Creating one...\n";
    $sectoralAdmin = User::create([
        'username' => 'sectoral_admin',
        'email' => 'sectoral@example.com',
        'password' => Hash::make('password'),
        'role' => 'sectoral_admin',
        'verification_status' => 'verified',
    ]);
    echo "Sectoral admin created.\n";
}

// Get or create profile
$profile = UserProfile::where('user_id', $sectoralAdmin->id)->first();

if (!$profile) {
    $profile = UserProfile::create([
        'user_id' => $sectoralAdmin->id,
        'first_name' => 'Sectoral',
        'last_name' => 'Admin',
        'phone' => '+212600000000',
        'address' => 'Admin Address',
        'city' => 'Casablanca',
        'region' => 'Casablanca-Settat',
        'postal_code' => '20000',
        'country' => 'Morocco',
        'profile_type' => 'organization',
    ]);
    echo "Profile created for sectoral admin.\n";
}

// Set sector to "Services" to match one of our submissions
$profile->sector = 'Services';
$profile->save();

echo "Sectoral admin sector set to: Services\n";
echo "Sectoral admin ID: {$sectoralAdmin->id}\n";
echo "Profile ID: {$profile->id}\n";

// Check how many submissions match this sector
use App\Models\InvestmentSubmission;
use App\Models\ProjectCarrierSubmission;
use App\Models\IdeaCarrierSubmission;
use App\Models\AutoEntrepreneurSubmission;

$servicesInvestments = InvestmentSubmission::where('sector', 'Services')->count();
$servicesProjectCarrier = ProjectCarrierSubmission::where('sector', 'Services')->count();
$servicesIdeaCarrier = IdeaCarrierSubmission::where('sector', 'Services')->count();
$servicesAutoEntrepreneur = AutoEntrepreneurSubmission::where('sector', 'Services')->count();

echo "\nSubmissions in 'Services' sector:\n";
echo "  - Investment: {$servicesInvestments}\n";
echo "  - Project Carrier: {$servicesProjectCarrier}\n";
echo "  - Idea Carrier: {$servicesIdeaCarrier}\n";
echo "  - Auto Entrepreneur: {$servicesAutoEntrepreneur}\n";
echo "  - TOTAL: " . ($servicesInvestments + $servicesProjectCarrier + $servicesIdeaCarrier + $servicesAutoEntrepreneur) . "\n";














