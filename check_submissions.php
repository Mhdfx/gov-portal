<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\InvestmentSubmission;
use App\Models\ProjectCarrierSubmission;
use App\Models\IdeaCarrierSubmission;
use App\Models\AutoEntrepreneurSubmission;
use App\Models\INDHSubmission;
use App\Models\TrainingSubmission;
use Illuminate\Support\Facades\Auth;

echo "=== Checking Submissions ===\n\n";

// Check all submissions
$investmentCount = InvestmentSubmission::count();
$projectCarrierCount = ProjectCarrierSubmission::count();
$ideaCarrierCount = IdeaCarrierSubmission::count();
$autoEntrepreneurCount = AutoEntrepreneurSubmission::count();
$indhCount = INDHSubmission::count();
$trainingCount = TrainingSubmission::count();

echo "Total Submissions:\n";
echo "  - Investment: {$investmentCount}\n";
echo "  - Project Carrier: {$projectCarrierCount}\n";
echo "  - Idea Carrier: {$ideaCarrierCount}\n";
echo "  - Auto Entrepreneur: {$autoEntrepreneurCount}\n";
echo "  - INDH: {$indhCount}\n";
echo "  - Training: {$trainingCount}\n";
echo "  - TOTAL: " . ($investmentCount + $projectCarrierCount + $ideaCarrierCount + $autoEntrepreneurCount + $indhCount + $trainingCount) . "\n\n";

// Check submissions by user mehdi
$mehdi = User::where('username', 'mehdi')->first();
if ($mehdi) {
    echo "Submissions by user 'mehdi' (ID: {$mehdi->id}):\n";
    $mehdiInvestments = InvestmentSubmission::where('user_id', $mehdi->id)->count();
    $mehdiProjectCarrier = ProjectCarrierSubmission::where('user_id', $mehdi->id)->count();
    $mehdiIdeaCarrier = IdeaCarrierSubmission::where('user_id', $mehdi->id)->count();
    $mehdiAutoEntrepreneur = AutoEntrepreneurSubmission::where('user_id', $mehdi->id)->count();
    $mehdiINDH = INDHSubmission::where('user_id', $mehdi->id)->count();
    $mehdiTraining = TrainingSubmission::where('user_id', $mehdi->id)->count();
    
    echo "  - Investment: {$mehdiInvestments}\n";
    echo "  - Project Carrier: {$mehdiProjectCarrier}\n";
    echo "  - Idea Carrier: {$mehdiIdeaCarrier}\n";
    echo "  - Auto Entrepreneur: {$mehdiAutoEntrepreneur}\n";
    echo "  - INDH: {$mehdiINDH}\n";
    echo "  - Training: {$mehdiTraining}\n";
    echo "  - TOTAL: " . ($mehdiInvestments + $mehdiProjectCarrier + $mehdiIdeaCarrier + $mehdiAutoEntrepreneur + $mehdiINDH + $mehdiTraining) . "\n\n";
    
    // Show recent submissions
    echo "Recent submissions by mehdi:\n";
    $recentInvestments = InvestmentSubmission::where('user_id', $mehdi->id)->latest()->take(3)->get();
    foreach ($recentInvestments as $sub) {
        echo "  - Investment #{$sub->submission_number}: {$sub->project_name} (Status: {$sub->status}, Sector: {$sub->sector})\n";
    }
    
    $recentProjectCarrier = ProjectCarrierSubmission::where('user_id', $mehdi->id)->latest()->take(3)->get();
    foreach ($recentProjectCarrier as $sub) {
        echo "  - Project Carrier #{$sub->submission_number}: {$sub->project_name} (Status: {$sub->status}, Sector: {$sub->sector})\n";
    }
    
    $recentIdeaCarrier = IdeaCarrierSubmission::where('user_id', $mehdi->id)->latest()->take(3)->get();
    foreach ($recentIdeaCarrier as $sub) {
        echo "  - Idea Carrier #{$sub->submission_number}: {$sub->idea_title} (Status: {$sub->status}, Sector: {$sub->sector})\n";
    }
    
    $recentAutoEntrepreneur = AutoEntrepreneurSubmission::where('user_id', $mehdi->id)->latest()->take(3)->get();
    foreach ($recentAutoEntrepreneur as $sub) {
        echo "  - Auto Entrepreneur #{$sub->submission_number}: {$sub->business_name} (Status: {$sub->status}, Sector: {$sub->sector})\n";
    }
    
    $recentINDH = INDHSubmission::where('user_id', $mehdi->id)->latest()->take(3)->get();
    foreach ($recentINDH as $sub) {
        echo "  - INDH #{$sub->submission_number}: {$sub->project_title} (Status: {$sub->status})\n";
    }
    
    $recentTraining = TrainingSubmission::where('user_id', $mehdi->id)->latest()->take(3)->get();
    foreach ($recentTraining as $sub) {
        echo "  - Training #{$sub->submission_number}: {$sub->training_title} (Status: {$sub->status})\n";
    }
} else {
    echo "User 'mehdi' not found!\n";
}

// Check admin dashboard stats
echo "\n=== Admin Dashboard Stats ===\n";
$totalSubmissions = $investmentCount + $projectCarrierCount + $ideaCarrierCount + $autoEntrepreneurCount + $indhCount + $trainingCount;
$pendingSubmissions = InvestmentSubmission::where('status', 'pending')->count() +
                      ProjectCarrierSubmission::where('status', 'pending')->count() +
                      IdeaCarrierSubmission::where('status', 'pending')->count() +
                      AutoEntrepreneurSubmission::where('status', 'pending')->count() +
                      INDHSubmission::where('status', 'pending')->count() +
                      TrainingSubmission::where('status', 'pending')->count();

echo "Total Submissions: {$totalSubmissions}\n";
echo "Pending Submissions: {$pendingSubmissions}\n";

// Check sectoral admin - need to find a sectoral admin user
echo "\n=== Sectoral Admin Check ===\n";
$sectoralAdmins = User::where('role', 'sectoral_admin')->get();
if ($sectoralAdmins->count() > 0) {
    foreach ($sectoralAdmins as $admin) {
        echo "Sectoral Admin: {$admin->username} (ID: {$admin->id})\n";
        if ($admin->profile) {
            $sector = $admin->profile->sector ?? $admin->profile->business_sector ?? 'N/A';
            echo "  Sector: {$sector}\n";
            
            // Count submissions for this sector
            $sectorInvestments = InvestmentSubmission::where('sector', $sector)->count();
            $sectorProjectCarrier = ProjectCarrierSubmission::where('sector', $sector)->count();
            $sectorIdeaCarrier = IdeaCarrierSubmission::where('sector', $sector)->count();
            $sectorAutoEntrepreneur = AutoEntrepreneurSubmission::where('sector', $sector)->count();
            
            echo "  Submissions in this sector:\n";
            echo "    - Investment: {$sectorInvestments}\n";
            echo "    - Project Carrier: {$sectorProjectCarrier}\n";
            echo "    - Idea Carrier: {$sectorIdeaCarrier}\n";
            echo "    - Auto Entrepreneur: {$sectorAutoEntrepreneur}\n";
        } else {
            echo "  No profile found\n";
        }
    }
} else {
    echo "No sectoral admin users found\n";
}

echo "\n=== Check Complete ===\n";














